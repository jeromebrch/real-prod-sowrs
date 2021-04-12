<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Category;
use App\Entity\Commitment;
use App\Entity\Cv;
use App\Entity\Media;
use App\Entity\Message;
use App\Entity\Recruiter;
use App\Form\ContactRecruiterType;
use App\Form\MessageType;
use App\Repository\CommitmentRepository;
use App\Repository\RecruiterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class RecruiterController extends AbstractController
{
    /**
     * @Route("/recruiter/profil", name="recruiter_profil")
     */
    public function recruiterProfil(): Response
    {
        $user = $this->getUser();
        return $this->render('user/userProfil.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/candidate/recruiterprofil/{id}", name="show_recruiter_profil", requirements={"id":"\d+"})
     */
       public function sendMessage(EntityManagerInterface $em, Request $request, $id, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        //getting user/recruiter
        $recruteurRepo = $this->getDoctrine()->getRepository(Recruiter::class);
        $recruiter = $recruteurRepo->find($id);

        $CategoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $category = $CategoryRepo->find(2);

        //counting the unreaded messages
        $messageState = $em->getRepository(Message::class)->count(['userRecipient' => $user, 'state' => 'non lu']);
        $message = new Message();
        $formMessage = $this->createForm(MessageType::class, $message);
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
            if ($user instanceof Candidate) {
                $message->setUserRecipient($recruiter);
                $message->setUserSender($user);
                $message->setCategory($category);
                $message->setState('non lu');
                $message->setCreatedAt(new \DateTimeImmutable());

                $em->persist($message);
                $em->flush();

                //sending email
                $email = (new Email())
                    ->from('team@sowrs.com')
                    ->to('kennouche.annelise@gmail.com')//todo :email du destinataire
                    ->subject($message->getSubject())
                    ->text($this->renderView(
                    // getting text for email from html page
                        'textEmail/emailTextRecruiter.html.twig',
                        ['message' => $message]
                    ), 'text/html');

                $mailer->send($email);
            }

            $this->addFlash('success', 'Votre message a bien été envoyé!');
            return $this->render('candidate/showRecruiter.html.twig', [
                'formMessage' => $formMessage->createView(),
                'message' => $message,
                'nonlu' => $messageState,
                'recruiter'=> $recruiter
            ]);
        }

        return $this->render('candidate/showRecruiter.html.twig', [
            'controller_name' => 'MessagingController',
            'formMessage' => $formMessage->createView(),
            'category' => $category,
            'nonlu' => $messageState,
            'recruiter'=> $recruiter

        ]);
    }



    /**
     * @Route("/engagement", name="set_commitment")
     */
    public function setCommitment(Request $req, EntityManagerInterface $em, CommitmentRepository $commitmentRepo) :JsonResponse {
        $user = $this->getUser();
        //récupération de la donnée JSON sous forme de tableau associatif
        $var[] = json_decode($req->getContent(), true);
        $value = $var[0]['commitment'];
        if($user instanceof Candidate){
            $textarea = $var[0]['textareaValue'];
        }

        //Verify if the commitment already exist
        if ($value){
            $commitments = $commitmentRepo->findAll();
            $userCommitment = null;
            foreach($commitments as $commitment){
                if(strtolower($value) == strtolower($commitment->getTitle()) && $user instanceof Candidate){
                    if(strtolower($textarea) == strtolower($commitment->getText()))
                    $userCommitment = $commitment;
                }
                elseif(strtolower($value) == strtolower($commitment->getTitle()) && $user instanceof Recruiter){
                    $userCommitment = $commitment;
                }
            }
            // persist the commitment if he doesn't exist
            if(!$userCommitment){
                $commitment = new Commitment();
                $commitment->setTitle(ucfirst($value));
                if($user instanceof Candidate){
                    $commitment->setText(ucfirst($textarea));
                }
                $em->persist($commitment);
                $em->flush();
                if($user instanceof Candidate){
                    $userCommitment = $commitmentRepo->findOneBy(['title' => $value, 'text' => $textarea]);
                }
                elseif ($user instanceof Recruiter){
                    $userCommitment = $commitmentRepo->findOneBy(['title' => $value]);
                }

            }
            // set the commitment to the user
            if($userCommitment){
                if($user instanceof Candidate or $user instanceof Recruiter){
                    $user->addCommitment($userCommitment);
                    $em->persist($user);
                    $em->flush();
                }
            }
            $em->flush();
            return new JsonResponse([ //Retourne la réponse JSON en cas de succès
                'success' => true
            ]);
        }else{
            return new JsonResponse([
                'success' => false
            ]);
        }
    }
}
