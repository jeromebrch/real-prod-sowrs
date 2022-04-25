<?php

namespace App\Controller;

use App\Repository\CandidateRepository;
use App\Repository\JobOfferRepository;
use App\Repository\RecruiterRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackOfficeController extends AbstractController
{
    /**
     * FOR GOING IN THE BACK OFFICE
     * @Route("/backOffice", name="back_office")
     */
    public function index(CandidateRepository $candidateRepo, RecruiterRepository $recruiterRepo, JobOfferRepository $offerRepo): Response
    {
        $allCandidate = $candidateRepo->findAll();
        $allRecruiter = $recruiterRepo->findAll();
        $allJobOffer = $offerRepo->findAll();
        return $this->render('back_office/index.html.twig', [
            'allCandidate' => $allCandidate,
            'allRecruiter' => $allRecruiter,
            'allOffer' => $allJobOffer
        ]);
    }

    /**
     * FOR DELETING A USER
     * @Route("/deleteUser/{id}", name="delete_user")
     */
    public function deleteUser($id, UserRepository $userRepo, EntityManagerInterface $em){
        try{
            $user = $userRepo->find($id);
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'L\'utilisateur a bien été supprimé !');
        }catch (\Exception $exception){
            $this->addFlash('error', 'Problème pendant la suppression !');
        }
        return $this->redirectToRoute('back_office', [

        ]);
    }

    /**
     * FOR DELETING AN OFFER
     * @Route("/deleteOfferAdmin/{id}", name="delete_offer_admin")
     */
    public function deleteOffer($id, JobOfferRepository $offerRepo, EntityManagerInterface $em){
        try{
            $em->remove($offerRepo->find($id));
            $em->flush();
            $this->addFlash('success', 'Offre supprimée');
        }catch (\Exception $exception){
            $this->addFlash('error', 'Problème pendant la suppression');
        }

        return $this->redirectToRoute('back_office', [

        ]);

    }
}
