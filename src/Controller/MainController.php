<?php

namespace App\Controller;

use App\Data\SearchCandidate;
use App\Data\SearchJobOffers;
use App\Entity\Recruiter;
use App\Form\SearchCandidateType;
use App\Form\SearchJobOfferType;
use App\Repository\CandidateRepository;
use App\Repository\JobOfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * returns the list of current offers linked to a search filter
     * @Route("/jobOffersList", name="main_job_offers_list")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param JobOfferRepository $jobOfferRepo
     * @return Response
     */
    public function jobOffersList(Request $request, PaginatorInterface $paginator, JobOfferRepository $jobOfferRepo): Response
    {
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
     * return the legal notice page
     * @Route("/legalNotice", name="main_legalNotice")
     * @return Response
     */
    public function legalNotice(): Response
    {
        return $this->render('legalNotice/legalNotice.html.twig');
    }
}
