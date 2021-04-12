<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Category;
use App\Entity\Cv;
use App\Entity\Media;
use App\Entity\Message;
use App\Entity\Recruiter;
use App\Form\MessageType;
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
     * @Route("/messaging/sendMessage/{id}", name="send_message")
     */
    public function sendMessageCandidate(EntityManagerInterface $em, Request $request, $id, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        //getting user/candidate
        $candidateRepo = $this->getDoctrine()->getRepository(Candidate::class);
        $candidate = $candidateRepo->find($id);

        //getting user/recruiter
        $recruiterRepo = $this->getDoctrine()->getRepository(Recruiter::class);
        $recruteur = $recruiterRepo->find($id);

        $CategoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $category = $CategoryRepo->find(2);

        $messages = $user->getMessages();
        //counting the unreaded messages
        $messageState = $em->getRepository(Message::class)->count(['userRecipient' => $user, 'state' => 'non lu']);

        $formMessage = $this->createForm(MessageType::class);
        $formMessage->handleRequest($request);

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
                    $cvFile->move($this->getParameter('cv'), $cv->getCvName());
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
                $message->setCategory($category);
                $message->setState('non lu');
                $message->setCreatedAt(new \DateTimeImmutable());

                $em->persist($message);
                $em->flush();

                //sending email
                $email = (new Email())
                    ->from('team@sowrs.com')
                    ->to('kennouche.annelise@gmail.com') //todo :email du destinataire
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


                ]);
            }

        return $this->render('messaging/sendMessage.html.twig', [
            'controller_name' => 'MessagingController',
            'formMessage' => $formMessage->createView(),
            'category' => $category,
            'candidate' => $candidate,
            'nonlu' => $messageState

        ]);
    }

}

