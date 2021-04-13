<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Cv;
use App\Entity\Recruiter;
use App\Form\CandidateModificationType;
use App\Form\CreateCvType;
use App\Form\RecognitionType;
use App\Form\RecruiterModificationType;
use App\Repository\CauseRepository;
use App\Repository\DevelopmentGoalsRepository;
use App\Repository\JobOfferRepository;
use App\Repository\PostRepository;
use App\Repository\RecognitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class DashBoardController extends AbstractController
{

    /**
     * return the dashboard
     * @Route("/user/dashBoard", name="main_dash_board")
     * @param JobOfferRepository $jobOfferRepo
     * @param PostRepository $postRepo
     * @return Response
     */
    public function dashBoard(JobOfferRepository $jobOfferRepo, PostRepository $postRepo): Response {
        $latestPosts = $postRepo->findLatestPost();
        if ($this->getUser() instanceof Recruiter) {
            $jobOffers = $jobOfferRepo->findJobOffersByRecruiterId($this->getUser()->getId());

            return $this->render('dash_board/dashBoard.html.twig', [
                'jobOffers' => $jobOffers,
                'latestPosts' => $latestPosts
            ]);
        } else {
            return $this->render('dash_board/dashBoard.html.twig', [
                'latestPosts' => $latestPosts
            ]);
        }

    }


}
