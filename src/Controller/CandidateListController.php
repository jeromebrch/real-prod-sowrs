<?php

namespace App\Controller;

use App\Data\SearchCandidate;
use App\Form\SearchCandidateType;
use App\Repository\CandidateRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidateListController extends AbstractController
{

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
        $user = $this->getUser();
        $data = new SearchCandidate();
        $formSearch = $this->createForm(SearchCandidateType::class, $data);
        $formSearch->handleRequest($request);

        $donnees = $repoCandidate->searchCandidate($data);
        $candidates = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5
        );
        $candidateList = $repoCandidate->findAll();
        $userFavorites = $user->getFavorites();

        return $this->render('main/candidateList.html.twig', [
            'formSearch' => $formSearch->createView(),
            'listCandidates' => $candidates,
            'candidates' => $candidateList,
            'favorites' => $userFavorites
        ]);
    }
}

