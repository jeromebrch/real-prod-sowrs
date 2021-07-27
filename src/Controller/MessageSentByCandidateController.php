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

class MessageSentByCandidateController extends AbstractController
{
    /**
     * @Route("/candidate/recruiterprofil/{id}", name="show_recruiter_profil", requirements={"id":"\d+"})
     * @param EntityManagerInterface $em
     * @param $id
     * @param MailerInterface $mailer
     * @param Request $request
     * @return Response
     */
    public function sendMessage(EntityManagerInterface $em, Request $request, $id, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        //getting user/recruiter
        $recruteurRepo = $this->getDoctrine()->getRepository(Recruiter::class);
        $recruiter = $recruteurRepo->find($id);

        $category = $this->getDoctrine()->getRepository(Category::class)->findOneByName('message');
        $category = $category[0];

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

                //Récupère le mail alternatif
                $alternateMail = $recruiter->getAlternateMail();

                //Check si il est rentré
                if($alternateMail != NULL) {

                    //sending email alternatif
                    $email = (new Email())
                        ->from('team@sowrs.com')
                        ->to($alternateMail)
                        ->subject($message->getSubject())
                        ->text($this->renderView(
                        // getting text for email from html page
                            'textEmail/emailTextRecruiter.html.twig',
                            ['message' => $message, 'recruiter' => $recruiter]
                        ), 'text/html');

                }else {

                    //sending email basic
                    $email = (new Email())
                        ->from('team@sowrs.com')
                        ->to($recruiter->getEmail())
                        ->subject($message->getSubject())
                        ->text($this->renderView(
                        // getting text for email from html page
                            'textEmail/emailTextRecruiter.html.twig',
                            ['message' => $message, 'recruiter' => $recruiter]
                        ), 'text/html');

                }
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



}
