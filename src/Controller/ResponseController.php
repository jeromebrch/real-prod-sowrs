<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Cv;
use App\Entity\Media;
use App\Entity\Message;
use App\Entity\Recruiter;
use App\Entity\User;
use App\Form\ResponseMessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ResponseController extends AbstractController
{
    /**
     * @Route("/messaging/sendMessageResponse/{id}", name="response")
     * @param EntityManagerInterface $em
     * @param $id
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     */
    public function sendMessageResponse($id, EntityManagerInterface $em, Request $request, MailerInterface $mailer): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        //getting received message for response
        $messageRecu = $this->getDoctrine()->getRepository(Message::class)->find($id);

        $userSender = $messageRecu->getUserSender();

        $formMessage = $this->createForm(ResponseMessageType::class)->handleRequest($request);

        $messages = $user->getReceivedMessages();

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
            return $this->redirectToRoute('messaging');
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
}
