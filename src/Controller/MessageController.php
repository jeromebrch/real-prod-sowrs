<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Category;
use App\Entity\Cv;
use App\Entity\JobOffer;
use App\Entity\Media;
use App\Entity\Message;
use App\Entity\Recruiter;
use App\Entity\User;
use App\Form\ApplyType;
use App\Form\MessageType;
use App\Form\ResponseMessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


class MessageController extends AbstractController
{
    /**
     * @Route("/messaging/messaging_page", name="messaging")
     */
    public function seeMessaging(EntityManagerInterface $em): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        //getting sended messages
        $messageRepo = $this->getDoctrine()->getRepository(Message::class);
        $messages = $messageRepo->findByUserRecipient($user);
        //counting unreaded messages
        $messageState = $em->getRepository(Message::class)->count(['userRecipient' => $user, 'state' => 'non lu']);
        $nonlu = 'non lu';
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepo->findAll();

        return $this->render('messaging/messaging_page.html.twig', [
            'controller_name' => 'MessagingController',
            'messages' => $messages,
            'category' => $category,
            'nonlus' => $messageState,
            'nonlu' => $nonlu
        ]);
    }


    /**
     * @Route("/messaging/readMessage/{id}", name="read_message")
     */
    public function readMessage($id, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        //counting unreaded messages
        $messageState = $em->getRepository(Message::class)->count(['userRecipient' => $user, 'state' => 'non lu']);
       //getting the message to read
        $messageRepo = $this->getDoctrine()->getRepository(Message::class);
        $message = $messageRepo->find($id);
        //setting the message state in readed
        $message->setState('lu');
        $em->persist($message);
        $em->flush();


        return $this->render('messaging/read_message.html.twig', [
            'controller_name' => 'MessagingController',
            'message' => $message,
            'nonlu' => $messageState
        ]);
    }


    /**
     * @Route("/messaging/delete/{id}", name="delete_message")
     */
    public function DeleteMessage($id, EntityManagerInterface $em): Response
    {
        $messageRepo = $this->getDoctrine()->getRepository(Message::class);
        $message = $messageRepo->find($id);
        if ($message) {
            $em->remove($message);
            $em->flush();

            $this->addFlash('success', 'Votre message a été supprimé');

            return $this->redirectToRoute('messaging');
        } else {
            return $this->render('error/notFound.html.twig');

        }
    }

    /**
     * @Route("/messaging/sendMessageApply/{id}", name="apply")
     */
    public function sendApply(Request $request, $id, MailerInterface $mailer): Response
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var User $userCurrent
         */
        $userCurrent = $this->getUser();
        //counting the unreaded messages
        $messageState = $em->getRepository(Message::class)->count(['userRecipient' => $userCurrent, 'state' => 'non lu']);
        //getting the joboffer
        $jobOffer = $em->getRepository(JobOffer::class)->find($id);
        $message = new Message();
        $formMessage = $this->createForm(ApplyType::class, $message);
        $formMessage->handleRequest($request);

        if ($formMessage->isSubmitted() && $formMessage->isValid()) {

            $cvFile = $formMessage->get('cvFile')->getData();
            $mediaFile = $formMessage->get('mediaFile')->getData();
            //saving cvFile
            if ($cvFile) {
                $cvFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $cv = new Cv();
                $cv->setCvName($cvFilename . '-' . uniqid() . '.' . $cvFile->guessExtension());
                try {
                    $cvFile->move($this->getParameter('cv'),$cv->getCvName());
                    $message->setCv($cv);
                    $em->persist($cv);
                } catch (FileException $e) {
                    $e->getMessage();
                }
            }//saving mediaFile
            if ($mediaFile) {
                $mediaFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                $media = new Media();
                $media->setMediaName($mediaFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension());
                try {
                    $mediaFile->move($this->getParameter('media'), $media->getMediaName());
                    $message->setMedia($media);
                    $em->persist($media);

                } catch (FileException $e) {
                    $e->getMessage();
                }
            }

            //creating message
            $message->setSubject("candidature pour annonce de " . $jobOffer->getTitle());
            $CategoryRepo = $this->getDoctrine()->getRepository(Category::class);
            $category = $CategoryRepo->find(1);
            $message->setCategory($category);
            $message->setUserRecipient($jobOffer->getEntity());
            $message->setUserSender($userCurrent);
            $message->setState('non lu');
            $message->setCreatedAt(new \DateTimeImmutable());


            $em->persist($message);
            $em->flush();

            //sending email
            $email = (new Email())
                ->from('team@sowrs.com')
                ->to('jerome.brch@gmail.com') //todo :email du destinataire
                ->subject($message->getSubject())
                ->text($this->renderView(
                // getting text for email from html page
                    'textEmail/textEmailApplyReceved.html.twig',
                    ['joboffer' => $jobOffer,
                        'message' => $message]
                ), 'text/html');

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé!');
        }

        return $this->render('messaging/sendMessageApply.html.twig', [
            'controller_name' => 'MessagingController',
            'formMessage' => $formMessage->createView(),
            'jobOffer' => $jobOffer,
            'messageState' => $messageState
        ]);
    }

    /**
     * @Route("/messaging/sendMessage/{id}", name="send_message")
     */
    public function sendMessage(EntityManagerInterface $em, Request $request, $id, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $message = new Message();

        //getting user/candidate
        $candidateRepo = $this->getDoctrine()->getRepository(Candidate::class);
        $candidate = $candidateRepo->find($id);
        //getting user/recruiter
        $recruteurRepo = $this->getDoctrine()->getRepository(Recruiter::class);
        $recruteur = $recruteurRepo->find($id);

        $CategoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $category = $CategoryRepo->findAll();

        $messages = $user->getMessages();
        //counting the unreaded messages
        $messageState = $em->getRepository(Message::class)->count(['userRecipient' => $user, 'state' => 'non lu']);

        $formMessage = $this->createForm(MessageType::class, $message, ['data' => $user]);
        $formMessage->handleRequest($request);

        if ($formMessage->isSubmitted() && $formMessage->isValid()) {

            $cvFile = $formMessage->get('cvFile')->getData();
            $mediaFile = $formMessage->get('mediaFile')->getData();
            //saving cvFile
            if ($cvFile) {
                $cvFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $cv = new Cv();
                $cv->setCvName($cvFilename . '-' . uniqid() . '.' . $cvFile->guessExtension());
                try {
                    $cvFile->move($this->getParameter('cv'),$cv->getCvName());
                    $message->setCv($cv);
                    $em->persist($cv);
                } catch (FileException $e) {
                    $e->getMessage();
                }
            }//saving mediaFile
            if ($mediaFile) {
                $mediaFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                $media = new Media();
                $media->setMediaName($mediaFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension());
                try {
                    $mediaFile->move($this->getParameter('media'), $media->getMediaName());
                    $message->setMedia($media);
                    $em->persist($media);

                } catch (FileException $e) {
                    $e->getMessage();
                }
            }
                //creating message
            if ($user instanceof Recruiter) {
                $message->setUserRecipient($candidate);
                $message->setUserSender($user);
                $message->setCategory(2);
                $message->setState('non lu');
                $message->setCreatedAt(new \DateTimeImmutable());

                $em->persist($message);
                $em->flush();

                //sending email
                $email = (new Email())
                    ->from('team@sowrs.com')
                    ->to('jerome.brch@gmail.com') //todo :email du destinataire
                    ->subject($message->getSubject())
                    ->text($this->renderView(
                    // getting text for email from html page
                        'textEmail/emailTextCandidate.html.twig',
                        ['message' => $message]
                    ), 'text/html');

                $mailer->send($email);

            } elseif ($user instanceof Candidate) {
                $message->setUserRecipient($recruteur);
                $message->setUserSender($user);
                $message->setCategory(2);
                $message->setState('non lu');
                $message->setCreatedAt(new \DateTimeImmutable());

                $em->persist($message);
                $em->flush();

                //sending email
                $email = (new Email())
                    ->from('team@sowrs.com')
                    ->to('jerome.brch@gmail.com')//todo :email du destinataire
                    ->subject($message->getSubject())
                    ->text($this->renderView(
                    // getting text for email from html page
                        'textEmail/emailTextRecruiter.html.twig',
                        ['message' => $message]
                    ), 'text/html');

                $mailer->send($email);
            }


            $this->addFlash('success', 'Votre message a bien été envoyé!');
            return $this->render('messaging/sendMessage.html.twig', [
                'formMessage' => $formMessage->createView(),
                'message' => $message,
                'nonlu' => $messageState
            ]);
        }

        return $this->render('messaging/sendMessage.html.twig', [
            'controller_name' => 'MessagingController',
            'formMessage' => $formMessage->createView(),
            'category' => $category,
            'message' => $message,
            'candidate' => $candidate,
            'nonlu' => $messageState

        ]);
    }

    /**
     * @Route("/messaging/sendMessageResponse/{id}", name="response")
     */
    public function sendMessageResponse($id, EntityManagerInterface $em, Request $request, MailerInterface $mailer): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        //getting received message for response
        $messageRepo = $this->getDoctrine()->getRepository(Message::class);
        $messageRecu = $messageRepo->find($id);

        $userSender = $messageRecu->getUserSender();

        $formMessage = $this->createForm(ResponseMessageType::class);
        $formMessage->handleRequest($request);

        $messages = $user->getMessages();

        //counting the unreaded messages
        $messageState = $em->getRepository(Message::class)->count(['userRecipient' => $user, 'state' => 'non lu']);

        if ($formMessage->isSubmitted() && $formMessage->isValid()) {

            $message = new Message();

            $cvFile = $formMessage->get('cvFile')->getData();
            $mediaFile = $formMessage->get('mediaFile')->getData();
            //saving cvFile
            if ($cvFile) {
                $cvFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $cv = new Cv();
                $cv->setCvName($cvFilename . '-' . uniqid() . '.' . $cvFile->guessExtension());
                try {
                    $cvFile->move($this->getParameter('cv'),$cv->getCvName());
                    $message->setCv($cv);
                    $em->persist($cv);
                } catch (FileException $e) {
                    $e->getMessage();
                }
            }//saving mediaFile
            if ($mediaFile) {
                $mediaFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                $media = new Media();
                $media->setMediaName($mediaFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension());
                try {
                    $mediaFile->move($this->getParameter('media'), $media->getMediaName());
                    $message->setMedia($media);
                    $em->persist($media);

                } catch (FileException $e) {
                    $e->getMessage();
                }
            }
            //creating message
            $message->setSubject('Re: ' . $messageRecu->getSubject());
            $message->setCategory($messageRecu->getCategory());
            $message->setUserRecipient($userSender);
            $message->setUserSender($user);
            $message->setBody($formMessage['body']->getData());
            $message->setState('non lu');
            $message->setCreatedAt(new \DateTimeImmutable());

            $em->persist($message);
            $em->flush();

            if ($user instanceof Candidate){
                //sending email
                $email = (new Email())
                    ->from('team@sowrs.com')
                    ->to('jerome.brch@gmail.com')//todo :email du destinataire
                    ->subject($message->getSubject())
                    ->text($this->renderView(
                    // getting text for email from html page
                        'textEmail/emailTextResponseCandidate.html.twig',
                        ['message' => $message,
                            'messageRecu' => $messageRecu]
                    ), 'text/html');

                $mailer->send($email);

            }elseif ($user instanceof Recruiter){
                //sending email
                $email = (new Email())
                    ->from('team@sowrs.com')
                    ->to('jerome.brch@gmail.com')//todo :email du destinataire
                    ->subject($message->getSubject())
                    ->text($this->renderView(
                    // getting text for email from html page
                        'textEmail/emailTextResponseRecruiter.html.twig',
                        ['message' => $message,
                        'messageRecu' => $messageRecu]
                    ), 'text/html');
                $mailer->send($email);

            }

            $this->addFlash('success', 'Votre message a bien été envoyé!');
            return $this->render('messaging/sendMessageResponse.html.twig', [
                'formMessage' => $formMessage->createView(),
                'message' => $message,
                'messageRecu' => $messageRecu,
                'nonlu' => $messageState,
                'userSender' => $userSender
            ]);
        }

        return $this->render('messaging/sendMessageResponse.html.twig', [
            'controller_name' => 'MessagingController',
            'formMessage' => $formMessage->createView(),
            'message' => $messages,
            'messageRecu' => $messageRecu,
            'nonlu' => $messageState,
            'userSender' => $userSender
        ]);

    }


    /**
     * @Route("/messaging/categoryMessage", name="category_message")
     */
   /* public function getCategoryMessage(Request $request): JsonResponse
    {  //méthode to sort messages from categories
        dd($request->request);
        return new JsonResponse(['cat' => 's']);
    }
    {% block javascripts %}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.5.4/umd/popper.min.js"></script>

    <script>

        var select = document.querySelector('#category');

        select.addEventListener('change', function (e) {
            var option = e.target[e.target.selectedIndex];
            var idCategory = option.value;
            var payload = {id:idCategory, a: 'demo'};

            var r = fetch('{{ path('category_message') }}',
                {
                    method: 'POST',
                    body: JSON.stringify(payload),
                    headers : {'Content-type': 'application/json'}
                })
                .then(response => console.log(response.json()))
        });

    </script>

    <div class="col-lg-12 ">
                            <label for="category">Filtrer: </label>
                            <select id="category" class="btn required" style="width: 250px; margin: 20px; background-color: white"  name="nameCat">
                               {% for categor in category %}
                                    <option value="{{ categor.id }}">{{ categor.name }}</option>
                                {% endfor %}
                            </select>
                    </div>
{% endblock %}
*/
}


