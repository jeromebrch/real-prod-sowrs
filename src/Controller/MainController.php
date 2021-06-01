<?php

namespace App\Controller;

use App\Data\SearchCandidate;
use App\Data\SearchJobOffers;
use App\Entity\Contact;
use App\Entity\Media;
use App\Entity\Recruiter;
use App\Form\ContactType;
use App\Form\SearchCandidateType;
use App\Form\SearchJobOfferType;
use App\Repository\CandidateRepository;
use App\Repository\ContactRepository;
use App\Repository\JobOfferRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
    public function index(Request $request,
                          PaginatorInterface $paginator,
                          JobOfferRepository $jobOfferRepo,
                          CandidateRepository $candidateRepo,
                          PostRepository $postRepo): Response
    {
        $user = $this->getUser();
        $data = new SearchJobOffers();
        $formSearchHome = $this->createForm(SearchJobOfferType::class, $data);
        $formSearchHome->handleRequest($request);
        $formSearchCandidate = $this->createForm(SearchCandidateType::class);
        $formSearchCandidate->handleRequest($request);
        $donnees = $jobOfferRepo->SearchJobOffers($data);
        $latestPosts = $postRepo->findLatestPost();
        $jobOffers = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5
        );

        if ($formSearchHome->isSubmitted() && $formSearchHome->isValid()) {
            $userFavoritesOffers = [];
            $userFavorites = $user->getFavorites();
            foreach($userFavorites as $favorite){
                array_unshift($userFavoritesOffers, [$favorite->getJobOffer(), $favorite->getId()]);
            }
            return $this->render('main/jobOffersListHome.html.twig', [
                'jobOffers' => $jobOffers,
                'favorites' => $userFavoritesOffers,
                'formSearchHome' => $formSearchHome->createView(),
                'user' => $user
            ]);
        }elseif($formSearchCandidate->isSubmitted() && $formSearchCandidate->isValid()){
            $candidates = $candidateRepo->searchCandidate($formSearchCandidate->getData());
            $listCandidates = $paginator->paginate(
                $candidates,
                $request->query->getInt('page', 1),
                5
            );
            return $this->render('main/candidateList.html.twig', [
                'formSearch' => $formSearchCandidate->createView(),
                'user' => $user,
                'listCandidates' => $listCandidates
            ]);
        }else{
            return $this->render('main/home.html.twig', [
                'formSearchHome' => $formSearchHome->createView(),
                'formSearch' => $formSearchCandidate->createView(),
                'user' => $user,
                'posts' => $latestPosts
            ]);
        }
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
