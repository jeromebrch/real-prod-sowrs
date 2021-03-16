<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Form\CandidateModificationType;
use App\Form\CreateCvType;
use App\Form\RecognitionType;
use App\Form\RecruiterModificationType;
use App\Repository\CauseRepository;
use App\Repository\DevelopmentGoalsRepository;
use App\Repository\JobOfferRepository;
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
     * returns a user’s change form
     * @Route("/user/myDetails", name="dashboard_details")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param RecognitionRepository $recognitionRepository
     * @param CauseRepository $causeRepository
     * @return Response
     */
    public function myDetails(Request $request, EntityManagerInterface $em,
                              RecognitionRepository $recognitionRepository,
                              CauseRepository $causeRepository,
                              MailerInterface $mailer,
                              SluggerInterface $slugger): Response
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

            if ($modifyUserForm->isSubmitted() && $modifyUserForm->isValid()) {

                $footPrintProofFile = $modifyUserForm->get('carbonFootPrintProof')->getData();
                if($footPrintProofFile){

                    $originalFilename = pathinfo($footPrintProofFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$footPrintProofFile->guessExtension();

                    try {
                        $footPrintProofFile->move(
                            $this->getParameter('carbonFootPrintProof_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Il y a eu une erreur lors de l\'upload du fichier');
                    }

                    // send email to sowrs with the PDF file in attachment
                    $email = (new Email())
                        ->from($this->getUser()->getEmail())
                        ->to('jerome.brch@gmail.com') // todo : mettre l'adresse de sowrs !!!
                        ->subject('Footprint proof from ' . $this->getUser()->getEmail())
                        ->text('La preuve de l\'empreint carbone')
                        ->attachFromPath('uploads/carbonFootPrintProof/' . $newFilename)
                        ->html('<p>Veuillez trouver ci-joint mon justificatif.</p><p>Cordialement</p>');

                    $mailer->send($email);

                    $user->setCarbonFootPrintProofFilename($newFilename);
                }

                $em->persist($user);
                $em->flush();
            }

            $candidate = new Candidate();
            $cv = $candidate->getCv();
            $formCv = $this->createForm(CreateCvType::class, $cv);


            return $this->render('dash_board/myDetails.html.twig', [
                'modifyUserForm' => $modifyUserForm->createView(),
                'listcauses' => $listeCauses,
                'recognitions' => $listeRecognitionRecruiter,
                'recognitionForm' => $recognitionForm->createView(),
                'user' => $user,
                'formCv'=>$formCv->createView()

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

    /**
     * Delete the user profil in DB, return to home page
     * @Route("/user/unsubscribe", name="unsubscribe")
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



}
