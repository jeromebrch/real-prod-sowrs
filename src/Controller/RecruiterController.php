<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Commitment;
use App\Entity\Recruiter;
use App\Entity\User;
use App\Repository\CommitmentRepository;
use App\Repository\DevelopmentGoalsRepository;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class RecruiterController extends AbstractController
{
    /**
     * @Route("/recruiter/profil", name="recruiter_profil")
     */
    public function recruiterProfil(): Response
    {
        $user = $this->getUser();
        return $this->render('user/userProfil.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * returns the list of current recruiter offers
     * @Route("/recruiter/myOffers", name="dash_board_my_offers")
     * @param JobOfferRepository $jobOfferRepo
     * @return Response
     */
    public function myOffers(JobOfferRepository $jobOfferRepo): Response
    {
        $id = $this->getUser()->getId();
        $jobOffers = $jobOfferRepo->findJobOffersByRecruiterId($id);

        return $this->render('dash_board/jobOffer/myOffers.html.twig', [
            'jobOffers' => $jobOffers,
        ]);
    }


    /**
     * returns recruiter's choice of sens rate
     * @param DevelopmentGoalsRepository $repository
     * @return Response
     * @Route("/user/senseRate/choices", name="dash_board_entitySenseRate")
     */
    public function entitySenseRate(DevelopmentGoalsRepository $repository): Response
    {
        $choices = $repository->findAll();

        return $this->render('dash_board/senseRate/choices.html.twig', [
            'choices' => $choices,
        ]);
    }

    /**
     * Delete the user profil in DB, return to home page
     * @Route("/user/unsubscribe", name="unsubscribe")
     * @param EntityManagerInterface $em
     */
    public function unsubscribe(EntityManagerInterface $em){

        $user = $this->getUser();

        //logout the user
        $session = $this->get('session');
        $session = new Session();
        $session->invalidate();

        //Delete user profil in DB
        $em->remove($user);
        $em->flush();

        $this->addFlash("success", "Le compte a bien été supprimé");

        return $this->redirectToRoute('main', [

        ]);

    }

    /**
     * setting recruiter's commitments
     * @Route("/engagement", name="set_commitment")
     * @param EntityManagerInterface $em
     * @param Request $req
     * @param CommitmentRepository $commitmentRepo
     * @return JsonResponse
     */
    public function setCommitment(Request $req, EntityManagerInterface $em, CommitmentRepository $commitmentRepo) :JsonResponse {
        $user = $this->getUser();
        //recovoring JSON datas
        $var[] = json_decode($req->getContent(), true);
        $value = $var[0]['commitment'];
        if($user instanceof Candidate){
            $textarea = $var[0]['textareaValue'];
        }
        //Verify if the commitment already exist
        if ($value){
            $commitments = $commitmentRepo->findAll();
            $userCommitment = null;
            foreach($commitments as $commitment){
                if(strtolower($value) == strtolower($commitment->getTitle()) && $user instanceof Candidate){
                    if(strtolower($textarea) == strtolower($commitment->getText()))
                    $userCommitment = $commitment;
                }
                elseif(strtolower($value) == strtolower($commitment->getTitle()) && $user instanceof Recruiter){
                    $userCommitment = $commitment;
                }
            }
            // persist the commitment if he doesn't exist
            if(!$userCommitment){
                $commitment = new Commitment();
                $commitment->setTitle(ucfirst($value));
                if($user instanceof Candidate){
                    $commitment->setText(ucfirst($textarea));
                }
                $em->persist($commitment);
                $em->flush();
                if($user instanceof Candidate){
                    $userCommitment = $commitmentRepo->findOneBy(['title' => $value, 'text' => $textarea]);
                }
                elseif ($user instanceof Recruiter){
                    $userCommitment = $commitmentRepo->findOneBy(['title' => $value]);
                }
            }
            // set the commitment to the user
            if($userCommitment){
                if($user instanceof Candidate or $user instanceof Recruiter){
                    $user->addCommitment($userCommitment);
                    $em->persist($user);
                    $em->flush();
                }
            }
            $em->flush();
            return new JsonResponse([ //Returns JSON success response
                'success' => true
            ]);
        }else{
            return new JsonResponse([
                'success' => false
            ]);
        }
    }

    /**
     * @Route("/deletevideo", name="delete_video_URL")
     */
    public function deleteVideoURL(EntityManagerInterface $em) :JsonResponse {

        $user = $this->getUser();
        if($user instanceof Recruiter){
            $user->setPresentationVideoURL("");
            $em->flush();
            return new JsonResponse([
                'success' => true
            ]);
        }else{
            return new JsonResponse([
                'success' => false
            ]);
        }

    }
}
