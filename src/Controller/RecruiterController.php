<?php

namespace App\Controller;

use App\Form\ContactRecruiterType;
use App\Repository\RecruiterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecruiterController extends AbstractController
{
    /**
     * @Route("/recruiter/profil", name="recruiter_profil")
     */
    public function recruiterProfil(): Response
    {
        $user = $this->getUser();
        return $this->render('recruiter/showRecruiterProfil.html.twig', [
            'recruiter' => $user,
        ]);
    }

    /**
     * @Route("/candidate/recruiterprofil/{id}", name="show_recruiter_profil", requirements={"id":"\d+"})
     */
    public function showRecruiterProfil($id, RecruiterRepository $recruiterRepo){
        $recruiter = $recruiterRepo->find($id);
        $contactRecruiterForm = $this->createForm(ContactRecruiterType::class);
        if($contactRecruiterForm->isSubmitted() && $contactRecruiterForm->isValid()){
            // todo : Anne-Lise -> gérer l'envoi du message dans la messagerie du recruteur
        }
        return $this->render('candidate/showRecruiter.html.twig', [
            'recruiter'=> $recruiter,
            'contactForm' => $contactRecruiterForm->createView()
        ]);
    }
}
