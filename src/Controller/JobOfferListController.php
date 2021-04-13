<?php

namespace App\Controller;

use App\Data\SearchJobOffers;
use App\Form\SearchJobOfferType;
use App\Repository\JobOfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobOfferListController extends AbstractController
{

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



}
