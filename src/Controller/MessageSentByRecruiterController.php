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


class MessageSentByRecruiterController extends AbstractController
{
    /**
     * @Route("/messaging/sendMessage/{id}", name="candidate_show")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param $id
     * @param MailerInterface $mailer
     * @return Response
     */
    public function sendMessageCandidate(EntityManagerInterface $em, Request $request, $id, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        //getting user/candidate
        $candidateRepo = $this->getDoctrine()->getRepository(Candidate::class);
        $candidate = $candidateRepo->find($id);

        $category = $this->getDoctrine()->getRepository(Category::class)->findOneByName('message');
        $category = $category[0];

        $messages = $user->getReceivedMessages();
        //counting the unreaded messages
        $messageState = $em->getRepository(Message::class)->count(['userRecipient' => $user, 'state' => 'non lu']);
        $message = new Message();
        $formMessage = $this->createForm(MessageType::class, $message)->handleRequest($request);

        if ($formMessage->isSubmitted() && $formMessage->isValid()) {

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
                    ->to($candidate->getEmail())
                    ->subject($message->getSubject())
                    ->text($this->renderView(
                    // getting text for email from html page
                        'textEmail/emailTextCandidate.html.twig',
                        ['message' => $message]
                    ), 'text/html');

                $mailer->send($email);

                $this->addFlash('success', 'Votre message a bien été envoyé!');
                return $this->render('recruiter/showCandidate.html.twig', [
                    'formMessage' => $formMessage->createView(),
                    'message' => $message,
                    'nonlu' => $messageState,
                    'candidate' => $candidate,
                ]);
            }
        }

        return $this->render('recruiter/showCandidate.html.twig', [
            'controller_name' => 'MessagingController',
            'formMessage' => $formMessage->createView(),
            'category' => $category,
            'message' => $message,
            'candidate' => $candidate,
            'nonlu' => $messageState

        ]);
    }



}


