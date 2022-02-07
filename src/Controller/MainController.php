<?php

namespace App\Controller;

use App\Data\SearchCandidate;
use App\Data\SearchJobOffers;
use App\Entity\Candidate;
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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use function mysql_xdevapi\getSession;

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
        $jobOffers->setUsedRoute('main_job_offers_list');
        if ($formSearchHome->isSubmitted() && $formSearchHome->isValid()) {
            $userFavoritesOffers = [];
            if($user instanceof Candidate){
                $userFavoritesOffers = $user->getFavoriteOffers()->getValues();
            }
            return $this->render('main/jobOffersListHome.html.twig', [
                'jobOffers' => $jobOffers,
                'favorites' => $userFavoritesOffers,
                'formSearchHome' => $formSearchHome->createView(),
                'user' => $user,
                'research' => false
            ]);
        }elseif($formSearchCandidate->isSubmitted() && $formSearchCandidate->isValid()){
            $candidates = $candidateRepo->searchCandidate($formSearchCandidate->getData());
            $userFavoritesOffers = [];
            if($user instanceof Recruiter){
                $userFavoritesOffers = $user->getFavoriteCandidates()->getValues();
            }
            $listCandidates = $paginator->paginate(
                $candidates,
                $request->query->getInt('page', 1),
                5
            );
            $listCandidates->setUsedRoute('main_candidate_list');
            return $this->render('main/candidateList.html.twig', [
                'formSearch' => $formSearchCandidate->createView(),
                'user' => $user,
                'listCandidates' => $listCandidates,
                'favorites' => $userFavoritesOffers,
                'research' => false
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

    /**
     * @Route("/closePopUp", name="close_pop_up")
     */
    public function closePopUp(Request $request): JsonResponse{
        try{
            $request->getSession()->set('close_pop_up', true);
            return new JsonResponse([
                'success' => true
            ]);
        }catch (\Exception $exception){
            return new JsonResponse([
                'success' => false,
                'error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * @Route("/bilanRSE", name="send_email_bilan_rse")
     */
    public function sendEmailRSE(MailerInterface $mailer):JsonResponse{
        try {
            $email = (new TemplatedEmail())
                ->from('team@sowrs.com')
//                ->to($this->getUser()->getEmail)
                ->to('jerome.brch@gmail.com')
                ->subject('Votre bilan RSE')
                ->htmlTemplate('textEmail/emailBilanRSE.html.twig');
            $mailer->send($email);
            return new JsonResponse([
                'success' => true
            ]);
        }catch (\Exception $exception){
            return new JsonResponse([
                'success' => false,
                'error' => $exception->getMessage()
            ]);
        }

    }
}
