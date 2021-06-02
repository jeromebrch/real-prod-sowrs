<?php

namespace App\Controller;


use App\Data\SearchJobOffers;
use App\Entity\Favorite;
use App\Form\SearchJobOfferType;
use App\Repository\FavoriteRepository;
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
     * @param PaginatorInterface $paginator
     * @param FavoriteRepository $favRepo
     * @param JobOfferRepository $offerRepo
     * @param $id
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function RemoveFavoriteOfferFromList($id, Request $request,FavoriteRepository $favRepo,PaginatorInterface $paginator,EntityManagerInterface $em, JobOfferRepository $offerRepo): Response
    {
        $data = new SearchJobOffers();
        $formSearch = $this->createForm(SearchJobOfferType::class, $data);
        $formSearch->handleRequest($request);
        $donnees = $offerRepo->SearchJobOffers($data);
        $jobOffers = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5
        );

        $user = $this->getUser();
        $favorite = $favRepo->find($id);

        if ($favorite) {
            try {
                $user->removeFavoriteOffer($favorite);
                $em->persist($favorite);
                $em->flush();

                $this->addFlash('success', 'l\'offre a été retirée de vos favoris');
            } catch (Exception $e) {
                $e->getMessage();
            }
        }
        $userFavorites = $user->getFavorites();
        $userFavoritesOffers = [];
        foreach($userFavorites as $favorite){
            array_unshift($userFavoritesOffers, [$favorite->getJobOffer(), $favorite->getId()]);
        }
        return $this->render('favorite/favorite_list.html.twig', [
            'favorite' => $favorite,
            'formSearch' => $formSearch->createView(),
            'jobOffers' => $jobOffers,
            'favorites' => $userFavoritesOffers
        ]);
    }

}
