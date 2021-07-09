<?php

namespace App\Controller;


use App\Data\SearchJobOffers;
use App\Entity\Candidate;
use App\Form\SearchJobOfferType;
use App\Repository\JobOfferRepository;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteJobofferController extends AbstractController
{

    /**
     * @Route("/favorite/addFavoriteOffer/{id}", name="add_favorite_offer")
     */
    function addFavoriteOffer($id, JobOfferRepository $offerRepo, EntityManagerInterface $em){
        $offer = $offerRepo->find($id);
        $user = $this->getUser();
        $userFavorites = $user->getFavoriteOffers()->getValues();
        if(!in_array($offer, $userFavorites)){
            $user->addFavoriteOffer($offer);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'L\'offre a bien été ajoutée de vos favoris');
        }
        return $this->redirectToRoute('main_job_offers_list');
    }


    /**
     * @Route("/favorite/removeOffer/{id}", name="remove_favorite_offer")
     */
    public function RemoveFavoriteOffer($id, JobOfferRepository $offerRepo, EntityManagerInterface $em): Response
    {
        $offer = $offerRepo->find($id);
        $user = $this->getUser();
        $userFavorites = $user->getFavoriteOffers()->getValues();
        if(in_array($offer, $userFavorites)){
            $user->removeFavoriteOffer($offer);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'L\'offre a bien été retirée de vos favoris');
        }
        return $this->redirectToRoute('main_job_offers_list');

    }

    /**
     * @Route("/favorite/removeOfferList/{id}", name="remove_favorite_offer_list")
     */
    public function RemoveFavoriteOfferFromList($id, Request $request, PaginatorInterface $paginator,EntityManagerInterface $em, JobOfferRepository $offerRepo): Response
    {
        $user = $this->getUser();
        $offer = $offerRepo->find($id);
        if($user instanceof Candidate){
            if(in_array($offer, $user->getFavoriteOffers()->getValues())){
                $user->removeFavoriteOffer($offer);
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'L\'offre a bien été retirée de vos favoris !');
            }
        }
        return $this->redirectToRoute('favorites');
    }
}
