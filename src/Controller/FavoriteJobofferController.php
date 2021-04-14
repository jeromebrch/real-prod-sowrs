<?php

namespace App\Controller;


use App\Data\SearchJobOffers;
use App\Entity\Favorite;
use App\Form\SearchJobOfferType;
use App\Repository\FavoriteRepository;
use App\Repository\JobOfferRepository;
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
     * @param PaginatorInterface $paginator
     * @param JobOfferRepository $offerRepo
     * @param $id
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function addFavoriteOffer($id, Request $request, PaginatorInterface $paginator,EntityManagerInterface $em, JobOfferRepository $offerRepo): Response
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
        $offer = $offerRepo->find($id);
        if ( !$user->getFavorites()->contains($offer )) {
            $favorite = new Favorite();
            $favorite->setJobOffer($offer);
            try {
                $user->addFavoriteOffer($favorite);
                $em->persist($favorite);
                $em->flush();

                $this->addFlash('success', 'l\'offre a été ajoutée à vos favoris');
            } catch (Exception $e) {
                $e->getMessage();
            }
        }
        return $this->render('main/jobOffersList.html.twig', [
            'favorite' => $user->getFavorites(),
            'formSearch' => $formSearch->createView(),
            'jobOffers' => $jobOffers,
            'offer' =>$offer

        ]);
    }


    /**
     * @Route("/favorite/removeOffer/{id}", name="remove_favorite_offer")
     * @param PaginatorInterface $paginator
     * @param FavoriteRepository $favRepo
     * @param JobOfferRepository $offerRepo
     * @param $id
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */

    public function RemoveFavoriteOffer($id, Request $request,FavoriteRepository $favRepo,PaginatorInterface $paginator,EntityManagerInterface $em, JobOfferRepository $offerRepo): Response
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

        try {
            $user->removeFavoriteOffer($favorite);
            $em->persist($favorite);
            $em->flush();

            $this->addFlash('success', 'l\'offre a été retirée de vos favoris');
        }catch (Exception $e){
            $e->getMessage();
        }

        return $this->render('main/jobOffersList.html.twig', [
            'favorite' => $favorite,
            'formSearch' => $formSearch->createView(),
            'jobOffers' => $jobOffers
        ]);

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


        try {
            $user->removeFavoriteOffer($favorite);
            $em->persist($favorite);
            $em->flush();

            $this->addFlash('success', 'l\'offre a été retirée de vos favoris');
        } catch (Exception $e) {
            $e->getMessage();
        }

        return $this->render('favorite/favorite_list.html.twig', [
            'favorite' => $favorite,
            'formSearch' => $formSearch->createView(),
            'jobOffers' => $jobOffers,
            'favorites' =>  $favorites = $user->getFavorites()
        ]);
    }

}
