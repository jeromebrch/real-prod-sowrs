<?php

namespace App\Controller;

use App\Data\SearchJobOffers;
use App\Entity\Category;
use App\Entity\Favorite;
use App\Entity\Message;
use App\Entity\User;
use App\Form\SearchJobOfferType;
use App\Repository\CvRepository;
use App\Repository\FavoriteRepository;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    /**
     * @Route("/favorite/favorite_page", name="favorites")
     */
    public function seeFavorites(EntityManagerInterface $em): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $favorites = $user->getFavorites();

        return $this->render('favorite/favorite_list.html.twig', [
            'favorites' => $favorites,

        ]);
    }

    /**
     * @Route("/favoriteaddFavoriteCv/{id}", name="add_favorite_cv")
     */
    public function addFavoriteCv($id, EntityManagerInterface $em, CvRepository $cvRepo): Response
    {
        $user = $this->getUser();
        $cv = $cvRepo->find($id);
        $favorite = new Favorite();
        $favorite->setCv($cv);

        $user->addFavoriteCv($favorite);
        $em->persist($favorite);
        $em->flush();

        $this->addFlash('success', 'le cv a été ajouté à vos favoris');
        return $this->render('messaging/read_message.html.twig', [

        ]);
    }

    /**
     * @Route("/favorite/addFavoriteOffer/{id}", name="add_favorite_offer")
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
     * @Route("/favorite/remove/{id}", name="remove_favorite")
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
     * @Route("/favorite/remove/{id}", name="remove_favorite")
     */
   /* public function RemoveFavoritecv($id, Request $request,FavoriteRepository $favRepo,EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $favorite = $favRepo->find($id);

        try {
        $user->removeFavoriteCv($favorite);
        $em->persist($favorite);
        $em->flush();

        $this->addFlash('success', 'l\'offre a été retirée de vos favoris');
        }catch (Exception $e){
            $e->getMessage();
        }

                return $this->render('main/jobOffersList.html.twig', [
                    'favorite' => $favorite,
                    'cv' => $cv
                ]);

                }*/

}