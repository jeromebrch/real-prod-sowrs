<?php

namespace App\Controller;

use App\Data\SearchCandidate;
use App\Entity\Favorite;
use App\Entity\User;
use App\Form\SearchCandidateType;
use App\Repository\CandidateRepository;
use App\Repository\CvRepository;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteCvController extends AbstractController
{
    //display favorite list
    /**
     * @Route("/favorite/favorite_page", name="favorites")
     * @param $em
     * @return Response
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
     * @Route("/favorite/addFavoriteCv/{id}", name="add_favorite_cv")
     * @param CandidateRepository $repoCandidate
     * @param PaginatorInterface $paginator
     * @param CvRepository $cvRepo
     * @param $id
     * @param EntityManagerInterface $em
     * @param Request $request
     */
    public function addFavoriteCv(CandidateRepository $repoCandidate, Request $request, PaginatorInterface $paginator,$id, EntityManagerInterface $em, CvRepository $cvRepo): Response
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
        $candidateList = $repoCandidate->findAll();

        $user = $this->getUser();
        $cv = $cvRepo->find($id);

        $favorite = new Favorite();
        $favorite->setCv($cv);

        $user->addFavoriteCv($favorite);
        $em->persist($favorite);
        $em->flush();

        $this->addFlash('success', 'le cv a été ajouté à vos favoris');
        return $this->render('main/candidateList.html.twig', [
            'cv'=>$cv,
            'formSearch' => $formSearch->createView(),
            'listCandidates' => $candidates,
            'candidates'=> $candidateList
        ]);
    }

    /**
     * @Route("/favorite/remove_cv/{id}", name="remove_favorite_cv")
     * @param CandidateRepository $repoCandidate
     * @param PaginatorInterface $paginator
     * @param $id
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function RemoveFavoritecv(CandidateRepository $repoCandidate, Request $request, PaginatorInterface $paginator,$id,FavoriteRepository $favRepo,EntityManagerInterface $em): Response
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
        $candidateList = $repoCandidate->findAll();

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

        return $this->render('main/candidateList.html.twig', [
            'favorite' => $favorite,
            'formSearch' => $formSearch->createView(),
            'listCandidates' => $candidates,
            'candidates'=> $candidateList
        ]);

    }
}