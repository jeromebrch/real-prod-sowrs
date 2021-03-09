<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Form\CandidateModificationType;
use App\Form\RecognitionType;
use App\Form\RecruiterModificationType;
use App\Repository\CauseRepository;
use App\Repository\DevelopmentGoalsRepository;
use App\Repository\JobOfferRepository;
use App\Repository\RecognitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashBoardController extends AbstractController
{
    /**
     * returns a user’s change form
     * @Route("/user/myDetails", name="dashboard_details")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param RecognitionRepository $recognitionRepository
     * @param CauseRepository $causeRepository
     * @return Response
     */
    public function myDetails(Request $request, EntityManagerInterface $em,
                              RecognitionRepository $recognitionRepository, CauseRepository $causeRepository): Response
    {
        //récupère l'utilisateur en cours
        $user = $this->getUser();

        if ($user instanceof Candidate) {
            $modifyUserForm = $this->createForm(CandidateModificationType::class, $user);
            $modifyUserForm->handleRequest($request);

            if ($modifyUserForm->isSubmitted() && $modifyUserForm->isValid()) {
                $em->persist($user);
                $em->flush();
            }
            return $this->render('dash_board/myDetails.html.twig', [
                'modifyUserForm' => $modifyUserForm->createView(),
            ]);
        } else {

            $modifyUserForm = $this->createForm(RecruiterModificationType::class, $user);
            $modifyUserForm->handleRequest($request);

            $listeCauses = $causeRepository->exclusionCauses($user);

            $listeRecognitionRecruiter = $recognitionRepository->findBy(["recruiter" => $this->getUser()]);
            $recognitionForm = $this->createForm(RecognitionType::class);
            dump($modifyUserForm);
            if ($modifyUserForm->isSubmitted() && $modifyUserForm->isValid()) {
                dump('jusque la c ok');
                $em->persist($user);
                $em->flush();
            }

            return $this->render('dash_board/myDetails.html.twig', [
                'modifyUserForm' => $modifyUserForm->createView(),
                'listcauses' => $listeCauses,
                'recognitions' => $listeRecognitionRecruiter,
                'recognitionForm' => $recognitionForm->createView()
            ]);
        }
    }

    //RECRUITER METHODS

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
}
