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
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function seeMessaging(): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        //récupère les messages reçus
        $messageRepo = $this->getDoctrine()->getRepository(Message::class);
        $messages = $messageRepo->findByUserRecipient($user);
        //trie les catégories de massages
        $candidature = 'candidature';
        //compte les messages non lus
        $nonLu = 'non lu';
        $count = 0;
        foreach ($messages as $message) {
            if ($message->getState() === $nonLu) {
                $count++;
            }
        }

        $mois = array(1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril', 5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août', 9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre');

        $categoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepo->findAll();

        return $this->render('messaging/messaging_page.html.twig', [
            'controller_name' => 'MessagingController',
            'messages' => $messages,
            'category' => $category,
            'nonlus' => $count,
            'nonlu' => $nonLu,
            'candidature' => $candidature

        ]);
    }


    /**
     * @Route("/messaging/readMessage/{id}", name="read_message")
     */
    public function readMessage($id, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $messages = $user->getMessages();
        $nonLu = 'non lu';
        $count = 0;

        foreach ($messages as $message) {
            if ($message->getState() === $nonLu) {
                $count++;
            }
        }

        $messageRepo = $this->getDoctrine()->getRepository(Message::class);
        $message = $messageRepo->find($id);

        $message->setState('lu');
        $em->persist($message);
        $em->flush();


        return $this->render('messaging/read_message.html.twig', [
            'controller_name' => 'MessagingController',
            'message' => $message,
            'nonlu' => $count
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
    public function sendApply($id, EntityManagerInterface $em, Request $request, MailerInterface $mailer): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $message = new Message();
        //récupère l'offre d'emploi
        $jobOfferRepo = $this->getDoctrine()->getRepository(JobOffer::class);
        $jobOffer = $jobOfferRepo->find($id);

        $messages = $user->getMessages();
        $nonLu = 'non lu';
        $count = 0;

        foreach ($messages as $message) {
            if ($message->getState() === $nonLu) {
                $count++;
            }
        }
        $formMessage = $this->createForm(ApplyType::class, $message);
        $formMessage->handleRequest($request);

        if ($formMessage->isSubmitted() && $formMessage->isValid()) {

            $cvFile = $formMessage->get('cvFile')->getData();
            $mediaFile = $formMessage->get('mediaFile')->getData();
            if ($cvFile) {
                $cvFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $cv = new Cv();
                $cv->setCvName($cvFilename . '-' . uniqid() . '.' . $cvFile->guessExtension());
                try {
                    $cvFile->move( 'file/cv/',$cv->getCvName());
                    $message->setCv($cv);
                    $em->persist($cv);
                    $em->flush();
                } catch (FileException $e) {
                    $e->getMessage();
                }
            }
            if ($mediaFile) {
                $mediaFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                $media = new Media();
                $media->setMediaName($mediaFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension());
                try {
                    $mediaFile->move('file/uploads/', $media->getMediaName());
                    $message->setMedia($media);
                    $em->persist($media);
                    $em->flush();
                } catch (FileException $e) {
                    $e->getMessage();
                }
            }
            //récupère le créateur de l'offre
            $recipient = $jobOffer->getEntity();
            $idCat = 1;
            //remplit les champs
            $message->setSubject('candidature pour l\'annonce' .' '. $jobOffer->getTitle());
            $CategoryRepo = $this->getDoctrine()->getRepository(Category::class);
            $category = $CategoryRepo->find($idCat);
            $message->setCategory($category);
            $message->setUserRecipient($recipient);
            $message->setUserSender($user);
            $message->setState('non lu');
            $message->setCreatedAt(new \DateTimeImmutable());
            $user->addMessages($message);

            $em->persist($message);
            $em->flush();

            //envoi de l'email
            $email = (new Email())
                ->from('team@sowrs.com')
                ->to('kennouche.annelise@gmail.com')//todo :email du destinataire
                ->subject($message->getSubject())
                ->text($message->getBody())
                ->text('Pour consulter votre message, rendez-vous sur www.sowrs.com');

            $mailer->send($email);


            $this->addFlash('succes', 'Votre message a bien été envoyé!');
            return $this->render('messaging/sendMessageApply.html.twig', [
                'formMessage' => $formMessage->createView(),
                'jobOffer' => $jobOffer,
                'nonlu' => $count
            ]);
        }
        return $this->render('messaging/sendMessageApply.html.twig', [
            'controller_name' => 'MessagingController',
            'formMessage' => $formMessage->createView(),
            'jobOffer' => $jobOffer,
            'nonlu' => $count
        ]);
    }

    /**
     * @Route("/messaging/sendMessage/{id}", name="send_message")
     */
    public function sendMessage(EntityManagerInterface $em, Request $request, $id, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $message = new Message();

        //récupère le user candidat
        $candidateRepo = $this->getDoctrine()->getRepository(Candidate::class);
        $candidate = $candidateRepo->find($id);
        //récupère le user recruteur
        $recruteurRepo = $this->getDoctrine()->getRepository(Recruiter::class);
        $recruteur = $recruteurRepo->find($id);

        $CategoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $category = $CategoryRepo->findAll();

        $messages = $user->getMessages();
        $nonLu = 'non lu';
        $count = 0;

        foreach ($messages as $message) {
            if ($message->getState() === $nonLu) {
                $count++;
            }
        }
        if ($user instanceof Recruiter) {
            $usernameRecruiter = [];
            array_push($usernameRecruiter, $user->getEntityName());
        } elseif ($user instanceof Candidate) {
            $usernameCandidate = [];
            array_push($usernameCandidate, $user->getFirstName() . ' ' . $user->getLastName());
        }
        $formMessage = $this->createForm(MessageType::class, $message, ['data' => $user]);
        $formMessage->handleRequest($request);

        $idCat = 2;

        if ($formMessage->isSubmitted() && $formMessage->isValid()) {

            $cvFile = $formMessage->get('cvFile')->getData();
            $mediaFile = $formMessage->get('mediaFile')->getData();
            if ($cvFile) {
                $cvFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $cv = new Cv();
                $cv->setCvName($cvFilename . '-' . uniqid() . '.' . $cvFile->guessExtension());
                try {
                    $cvFile->move($cv->getCvName());
                    $message->setCv($cv);
                    $em->persist($cv);
                    $em->flush();
                } catch (FileException $e) {
                    $e->getMessage();
                }
            }
            if ($mediaFile) {
                $mediaFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                $media = new Media();
                $media->setMediaName($mediaFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension());
                try {
                    $mediaFile->move($media->getMediaName());
                    $message->setMedia($media);
                    $em->persist($media);
                    $em->flush();
                } catch (FileException $e) {
                    $e->getMessage();
                }
            }

            if ($user instanceof Recruiter) {
                $message->setUserRecipient($candidate);
                $message->setUserSender($user);
                $message->setCategory($idCat);
                $message->setState('non lu');
                $message->setCreatedAt(new \DateTimeImmutable());

                $em->persist($message);
                $em->flush();
            } elseif ($user instanceof Candidate) {
                $message->setUserRecipient($recruteur);
                $message->setUserSender($user);
                $message->setCategory($idCat);
                $message->setState('non lu');
                $message->setCreatedAt(new \DateTimeImmutable());

                $em->persist($message);
                $em->flush();
            }

            //envoi de l'email
            $email = (new Email())
                ->from('team@sowrs.com')
                ->to('kennouche.annelise@gmail.com')//todo :email du destinataire
                ->subject($message->getSubject())
                ->text($message->getBody())
                ->text('Pour consulter votre message, rendez-vous sur www.sowrs.com');

            $mailer->send($email);

            $this->addFlash('succes', 'Votre message a bien été envoyé!');
            return $this->render('messaging/sendMessage.html.twig', [
                'formMessage' => $formMessage->createView(),
                'message' => $message,
                'nonlu' => $count
            ]);
        }

        return $this->render('messaging/sendMessage.html.twig', [
            'controller_name' => 'MessagingController',
            'formMessage' => $formMessage->createView(),
            'category' => $category,
            'message' => $message,
            'candidate' => $candidate,
            'usernameRecruiter' => $usernameRecruiter,
            'usernameCandidate' => $usernameCandidate,
            'nonlu' => $count

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

        $messageRepo = $this->getDoctrine()->getRepository(Message::class);
        $messageRecu = $messageRepo->find($id);

        $formMessage = $this->createForm(ResponseMessageType::class);
        $formMessage->handleRequest($request);

        $messages = $user->getMessages();
        $nonLu = 'non lu';
        $count = 0;

        foreach ($messages as $message) {
            if ($message->getState() === $nonLu) {
                $count++;
            }
        }

        if ($formMessage->isSubmitted() && $formMessage->isValid()) {

            $message = new Message();

            $cvFile = $formMessage->get('cvFile')->getData();
            $mediaFile = $formMessage->get('mediaFile')->getData();
            if ($cvFile) {
                $cvFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $cv = new Cv();
                $cv->setCvName('file/cv'.$cvFilename . '-' . uniqid() . '.' . $cvFile->guessExtension());
                try {
                    $cvFile->move($cv->getCvName());
                    $message->setCv($cv);
                    $em->persist($cv);
                    $em->flush();
                } catch (FileException $e) {
                    $e->getMessage();
                }
            }
            if ($mediaFile) {
                $mediaFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                $media = new Media();
                $media->setMediaName('file/uploads/'.$mediaFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension());
                try {
                    $mediaFile->move($media->getMediaName());
                    $message->setMedia($media);
                    $em->persist($media);
                    $em->flush();
                } catch (FileException $e) {
                    $e->getMessage();
                }
            }

            $message->setSubject('Re: ' . $messageRecu->getSubject());
            $message->setCategory($messageRecu->getCategory());
            $message->setUserRecipient($messageRecu->getUserSender());
            $message->setUserSender($user);
            $message->setBody($formMessage['body']->getData());
            $message->setState('non lu');
            $message->setCreatedAt(new \DateTimeImmutable());

            $em->persist($message);
            $em->flush();


            //envoi de l'email
            $email = (new Email())
                ->from('team@sowrs.com')
                ->to('kennouche.annelise@gmail.com')//todo :email du destinataire
                ->subject($message->getSubject())
                ->text($message->getBody())
                ->text('Pour consulter votre message, rendez-vous sur www.sowrs.com');

            $mailer->send($email);

            $this->addFlash('succes', 'Votre message a bien été envoyé!');
            return $this->render('messaging/sendMessageResponse.html.twig', [
                'formMessage' => $formMessage->createView(),
                'message' => $message,
                'messageRecu' => $messageRecu,
                'nonlu' => $count
            ]);
        }

        return $this->render('messaging/sendMessageResponse.html.twig', [
            'controller_name' => 'MessagingController',
            'formMessage' => $formMessage->createView(),
            'message' => $message,
            'messageRecu' => $messageRecu,
            'nonlu' => $count
        ]);

    }


   /**
     * @Route("/messaging/categoryMessage", name="category_message")
     */
    public function getCategoryMessage(Request $request): JsonResponse
    {
        dd($request->request);
        return new JsonResponse(['cat' => 's']);
    }

}


