<?php

namespace App\Controller;

use App\Repository\CandidateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidateController extends AbstractController
{
    /**
     * @Route("/recruiter/candidate/show/{id}", name="candidate_show", requirements={"id":"\d+"})
     * @param $id
     * @param CandidateRepository $candidateRepo
     * @return Response
     */
    public function showCandidate($id, CandidateRepository $candidateRepo): Response
    {
        $candidate = $candidateRepo->showOneCandidate($id);
        if ($candidate) {
            return $this->render('recruiter/showCandidate.html.twig', [
                'candidate' => $candidate,
            ]);
        } else {
            return $this->render('error/CandidateNotFound.html.twig');
        }
    }

    /**
     * Show the candidat profil (user)
     * @Route("/candidate/profil", name="candidat_profil")
     */
    public function candidatprofil(){
        $user = $this->getUser();
        return $this->render('user/userProfil.html.twig', [
            'user' => $user,
        ]);
    }

}
