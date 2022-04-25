<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Recruiter;
use App\Repository\CandidateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteCvController extends AbstractController
{
    /**
     * display favorite cv list
     * @Route("/favorite/favorite_page", name="favorites")
     */
    public function seeFavorites(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
        $userFavorites = [];

        if($user instanceof Candidate){
            $userFavorites = $user->getFavoriteOffers()->getValues();
        }elseif($user instanceof Recruiter){
            $userFavorites = $user->getFavoriteCandidates()->getValues();
        }

        //pagination
        $favorite = $paginator->paginate(
            $userFavorites,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('favorite/favorite_list.html.twig', [
            'favorites' => $userFavorites,
            'favorite' => $favorite
        ]);
    }

    /**
     * TO ADD A CV IN FAVORITES
     * @Route("/favorite/addFavoriteCv/{id}", name="add_favorite_cv")
     */
    public function addFavoriteCv($id, CandidateRepository $repoCandidate, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $candidate = $repoCandidate->find($id);
        if($user instanceof Recruiter){
            $userFavorites = $user->getFavoriteCandidates()->getValues();
            if(!in_array($candidate, $userFavorites)){
                $user->addFavoriteCandidate($candidate);
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Le candidat à bien été rajouté à vos favoris !');
            }
        }
        return $this->redirectToRoute('main_candidate_list');
    }

    /**
     * TO REMOVE A CV FROM FAVORITES
     * @Route("/favorite/remove_cv/{id}", name="remove_favorite_cv")
     */
    public function RemoveFavoritecv($id, CandidateRepository $repoCandidate, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $candidate = $repoCandidate->find($id);
        if($user instanceof Recruiter){
            $userFavorites = $user->getFavoriteCandidates()->getValues();
            if(in_array($candidate, $userFavorites)){
                $user->removeFavoriteCandidate($candidate);
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Le candidat à bien été retiré de vos favoris !');
            }
        }
        return $this->redirectToRoute('main_candidate_list');
    }

    /**
     * YOU CAN SEE IN THIS DUPLICATED CONTENT, IT'S A BIG MESS...
     * @Route("/favorite/remove_cv_list/{id}", name="remove_favorite_cv_list")
     */
    public function RemoveFavoritecvFromList($id, CandidateRepository $repoCandidate, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $candidate = $repoCandidate->find($id);
        if($user instanceof Recruiter){
            $userFavorites = $user->getFavoriteCandidates()->getValues();
            if(in_array($candidate, $userFavorites)){
                $user->removeFavoriteCandidate($candidate);
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Le candidat à bien été retiré de vos favoris !');
            }
        }
        return $this->redirectToRoute('favorites');

    }
}