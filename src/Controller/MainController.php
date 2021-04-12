<?php

namespace App\Controller;

use App\Data\SearchCandidate;
use App\Data\SearchJobOffers;
use App\Entity\Contact;
use App\Entity\Media;
use App\Entity\Recruiter;
use App\Form\ContactType;
use App\Form\SearchCandidateType;
use App\Form\SearchJobOfferType;
use App\Repository\CandidateRepository;
use App\Repository\ContactRepository;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * return the main page
     * @Route("/", name="main")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param JobOfferRepository $jobOfferRepo
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator, JobOfferRepository $jobOfferRepo): Response
    {
        $data = new SearchJobOffers();
        $formSearchHome = $this->createForm(SearchJobOfferType::class, $data);
        $formSearchHome->handleRequest($request);
        $donnees = $jobOfferRepo->SearchJobOffers($data);
        $jobOffers = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5
        );

        if ($formSearchHome->isSubmitted() && $formSearchHome->isValid()) {
            return $this->render('main/jobOffersListHome.html.twig', [
                'jobOffers' => $jobOffers,
                'formSearchHome' => $formSearchHome->createView(),
            ]);
        } else {

            return $this->render('main/home.html.twig', [
                'formSearchHome' => $formSearchHome->createView(),
            ]);
        }
    }

    /**
     * returns the page about sowrs
     * @Route("/aboutSowrs", name="main_about_sowrs")
     */
    public function aboutSowrs(): Response
    {
        return $this->render('main/aboutSowrs.html.twig');
    }


    /**
     * returns the page contact us
     * @Route("/contactUs", name="main_contact_us")
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
         * @Route("/main/contactUs/deleteFile/{id}", name="main_deleteFile", requirements={"id":"\d+"})
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

        /**
     * returns the list of current offers linked to a search filter
     * @Route("/jobOffersList", name="main_job_offers_list")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param JobOfferRepository $jobOfferRepo
     * @return Response
     */
    public function jobOffersList(Request $request, PaginatorInterface $paginator, JobOfferRepository $jobOfferRepo): Response
    {
        $user = $this->getUser();
        $data = new SearchJobOffers();
        $formSearch = $this->createForm(SearchJobOfferType::class, $data);
        $formSearch->handleRequest($request);
        $donnees = $jobOfferRepo->SearchJobOffers($data);
        $jobOffers = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('main/jobOffersList.html.twig', [
            'jobOffers' => $jobOffers,
            'favorites' => $user->getFavorites(),
            'formSearch' => $formSearch->createView(),
        ]);
    }




    /**
     * return the dashboard
     * @Route("/user/dashBoard", name="main_dash_board")
     * @param JobOfferRepository $jobOfferRepo
     * @return Response
     */
    public function dashBoard(JobOfferRepository $jobOfferRepo): Response
    {
        if ($this->getUser() instanceof Recruiter) {
            $jobOffers = $jobOfferRepo->findJobOffersByRecruiterId($this->getUser()->getId());

            return $this->render('dash_board/dashBoard.html.twig', [
                'jobOffers' => $jobOffers,
            ]);
        } else {
            return $this->render('dash_board/dashBoard.html.twig');
        }

    }

    /**
     * return the list of active candidates
     * @Route("/recruiter/candidateList", name="main_candidate_list")
     * @param CandidateRepository $repoCandidate
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function candidateList(CandidateRepository $repoCandidate, Request $request, PaginatorInterface $paginator): Response
    {
        $data = new SearchCandidate();
        $formSearch = $this->createForm(SearchCandidateType::class, $data);
        $formSearch->handleRequest($request);

        $donnees = $repoCandidate->searchCandidate($data);
        $candidates = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('main/candidateList.html.twig', [
            'formSearch' => $formSearch->createView(),
            'listCandidates' => $candidates,
        ]);
    }

    /**
     * @Route("/main/contactUs/delete", name="delete_file")
     */
    public function DeleteMessage(EntityManagerInterface $em):Response
    {
       // $fileRepo = $this->getDoctrine()->getRepository(Media::class);
       // $file = $fileRepo-                                //{id}
       // if ($file) {
           // $em->remove($file);
            $em->flush();

            $this->addFlash('success', 'Votre fichier a été supprimé');

            return $this->redirectToRoute('main_contact_us');
       // } else {
         //   return $this->render('error/notFound.html.twig');

       // }
    }

    /**
     * return the legal notice page
     * @Route("/legalNotice", name="main_legalNotice")
     * @return Response
     */
    public function legalNotice(): Response
    {
        return $this->render('legalNotice/legalNotice.html.twig');
    }
}
