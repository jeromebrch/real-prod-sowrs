<?php

namespace App\Controller;

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
        return $this->render('candidate/showRecruiter.html.twig', [
            'recruiter'=> $recruiter
        ]);
    }
}
