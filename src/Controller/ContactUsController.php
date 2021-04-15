<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactUsController extends AbstractController
{
    /**
     * returns the page contact us
     * @Route("/contactUs", name="main_contact_us")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     */
    public function contactUs(Request $request,MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $reCaptcha = new ReCaptcha($_ENV['GOOGLE_RECAPTCHA_SECRET']);
        $resp = $reCaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

        $fichier = $formContact['fichier']->getData();
        $autreFichier = $formContact['autreFichier']->getData();

        if ($formContact->isSubmitted() && $formContact->isValid() && $resp->isSuccess()) {

            $contact->setDestinataire('kennouche.annelise@gmail.com');
            $fichier = $contact->getFichier();
            $autreFichier = $contact->getAutreFichier();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();


            //envoi de l'email
            $email = (new Email())
                ->from($contact->getEmail())
                ->to($contact->getDestinataire())
                ->subject($contact->getNom())
                ->text($contact->getTelephone())
                ->text($contact->getMessage())
                ->attach($contact->getFichier())
                ->attach($contact->getAutreFichier());
            try {
                $mailer->send($email);

                $this->addFlash('succes', 'Votre email a bien été envoyé!');
                $this->redirectToRoute('main_dash_board');

            } catch (TransportExceptionInterface $e) {
                $e->getMessage();
            }

        }
        return $this->render('main/contactUs.html.twig', ['formContact'=>$formContact->createView(),
            'autreFichier' => $autreFichier,
            'fichier' => $fichier
        ]);
    }


    /**
     * @Route("/main/deleteFile/{id}", name="main_deleteFile", requirements={"id":"\d+"})
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function clearFile($id, ContactRepository $repositoryContact, EntityManagerInterface $entityManager): Response
    {

        $contactRepo = $repositoryContact->find($id);
        $file = $contactRepo->getFichier();
        if ($file) {
            $entityManager->clear($file);
            $entityManager->flush();
        }
        $otherFile = $contactRepo->getAutreFichier();
        if ($otherFile) {
            $entityManager->clear($otherFile);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Votre fichier à été supprimé');
        return $this->redirectToRoute('main_dash_board');
    }


}
