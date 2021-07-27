<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Cv;
use App\Entity\JobOffer;
use App\Entity\Media;
use App\Entity\Message;
use App\Entity\User;
use App\Form\ApplyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ApplyController extends AbstractController
{
    /**
     * @Route("/messaging/sendMessageApply/{id}", name="apply")
     * @param $id
     * @param MailerInterface $mailer
     * @param Request $request
     * @return Response
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
        $formMessage = $this->createForm(ApplyType::class, $message)->handleRequest($request);

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
            $category = $this->getDoctrine()->getRepository(Category::class)->findOneByName('Candidature');
            $message->setCategory($category[0]);
            $message->setUserRecipient($jobOffer->getEntity());
            $message->setUserSender($userCurrent);
            $message->setState('non lu');
            $message->setCreatedAt(new \DateTimeImmutable());


            $em->persist($message);
            $em->flush();

            //Récupère le mail alternatif
            $alternateMail = $jobOffer->getEntity()->getAlternateMail();

            //Check si il est rentré
            if($alternateMail != NULL) {

                //sending email alternatif
                $email = (new Email())
                    ->from('team@sowrs.com')
                    ->to($jobOffer->getEntity()->getalternateMail())
                    ->subject($message->getSubject())
                    ->text($this->renderView(
                    // getting text for email from html page
                        'textEmail/textEmailApplyReceved.html.twig',
                        ['joboffer' => $jobOffer,
                            'message' => $message]
                    ), 'text/html');

            }else {

                //sending email basic
                $email = (new Email())
                    ->from('team@sowrs.com')
                    ->to($jobOffer->getEntity()->getEmail())
                    ->subject($message->getSubject())
                    ->text($this->renderView(
                    // getting text for email from html page
                        'textEmail/textEmailApplyReceved.html.twig',
                        ['joboffer' => $jobOffer,
                            'message' => $message]
                    ), 'text/html');

            }

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé!');
            return $this->redirectToRoute('messaging', [
                'applieSended' => true
            ]);
        }

        return $this->render('messaging/sendMessageApply.html.twig', [
            'controller_name' => 'MessagingController',
            'formMessage' => $formMessage->createView(),
            'jobOffer' => $jobOffer,
            'messageState' => $messageState
        ]);
    }
}
