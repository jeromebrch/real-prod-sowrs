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
use App\Repository\PostRepository;
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
