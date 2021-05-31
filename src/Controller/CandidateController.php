<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\CandidateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidateController extends AbstractController
{
    /**
     * @Route("/recruiter/candidate/show/{id}", name="candidate_show", requirements={"id":"\d+"})
     * @param $id
     * @param CandidateRepository $candidateRepo
     * @param Request $request
     * @return Response
     */
    public function showCandidate($id, Request $request, CandidateRepository $candidateRepo): Response
    {
        $message = new Message();
        $formMessage = $this->createForm(MessageType::class, $message);
        $formMessage->handleRequest($request);
        $candidate = $candidateRepo->showOneCandidate($id);
        if ($candidate) {
            return $this->render('recruiter/showCandidate.html.twig', [
                'candidate' => $candidate,
                'formMessage' => $formMessage->createView(),
                'message' => $message
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

    /**
     * @Route("/candidate/applies", name="candidate_applies")
     */
    public function candidateApplies(){
        $user = $this->getUser();
        $userMessages = $user->getSendedMessages();
        $userApplies = [];
        foreach($userMessages as $messageID => $message){
            if($message->getCategory()->getName() == "Candidature"){
                array_push($userApplies, $message);
            }
        }

        return $this->render('candidate/applies.html.twig', [
            'applies' => $userApplies
        ]);
    }

}
