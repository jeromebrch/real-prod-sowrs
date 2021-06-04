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
     * display favorite list
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
     * @Route("/favorite/remove_cv/{id}", name="remove_favorite_cv")
     */
    public function RemoveFavoritecv($id, CandidateRepository $repoCandidate, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
//<<<<<<< HEAD
//        $favorite = $favRepo->find($id);
//
//        try {
//            $user->removeFavoriteCv($favorite);
//            $em->persist($favorite);
//            $em->flush();
//
//            $this->addFlash('success', 'Le cv a été retiré de vos favoris');
//        }catch (Exception $e){
//            $e->getMessage();
//=======
        $candidate = $repoCandidate->find($id);
        if($user instanceof Recruiter){
            $userFavorites = $user->getFavoriteCandidates()->getValues();
            if(in_array($candidate, $userFavorites)){
                $user->removeFavoriteCandidate($candidate);
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Le candidat à bien été retiré de vos favoris !');
            }
//>>>>>>> b9ed6813639f8d61b34de841d2f13d2020bfc8d6
        }
        return $this->redirectToRoute('main_candidate_list');
    }

    /**
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
//<<<<<<< HEAD
//                $this->addFlash('success', 'Le cv a été retirée de vos favoris');
//            } catch (Exception $e) {
//                $e->getMessage();
//=======
                $this->addFlash('success', 'Le candidat à bien été retiré de vos favoris !');
//>>>>>>> b9ed6813639f8d61b34de841d2f13d2020bfc8d6
            }
        }
        return $this->redirectToRoute('favorites');

    }
}