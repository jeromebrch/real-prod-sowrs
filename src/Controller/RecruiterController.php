<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Commitment;
use App\Entity\Recruiter;
use App\Form\ContactRecruiterType;
use App\Repository\CommitmentRepository;
use App\Repository\RecruiterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;

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

    /**
     * @Route("/engagement", name="set_commitment")
     */
    public function setCommitment(Request $req, EntityManagerInterface $em, CommitmentRepository $commitmentRepo) :JsonResponse {
        $user = $this->getUser();
        //récupération de la donnée JSON sous forme de tableau associatif
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
            return new JsonResponse([ //Retourne la réponse JSON en cas de succès
                'success' => true
            ]);
        }else{
            return new JsonResponse([
                'success' => false
            ]);
        }
    }
}
