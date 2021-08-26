<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Cv;
use App\Entity\Picture;
use App\Entity\Recruiter;
use App\Form\CandidateModificationType;
use App\Form\ChangePictureRecruiterType;
use App\Form\CreateCvType;
use App\Form\RecognitionType;
use App\Form\RecruiterModificationType;
use App\Repository\CauseRepository;
use App\Repository\RecognitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ModificationController extends AbstractController
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
        $user = $this->getUser();
        //modification form
        if ($user instanceof Candidate) {
            $modifyUserForm = $this->createForm(CandidateModificationType::class, $user);
            $modifyUserForm->handleRequest($request);
            $cv = new Cv();
            $formCv = $this->createForm(CreateCvType::class, $cv);

            if ($modifyUserForm->isSubmitted() && $modifyUserForm->isValid()) {
                $em->persist($user);
                $em->flush();
                $this->addFlash("success", "Vos modifications ont bien été enregistrées");
                if($user->getScoring()){
                    return $this->redirectToRoute('main_dash_board', [

                    ]);
                }else{
                    return $this->redirectToRoute('dash_board_entitySenseRate', [

                    ]);
                }
            }
            return $this->render('dash_board/myDetails.html.twig', [
                'modifyUserForm' => $modifyUserForm->createView(),
                'formCv'=>$formCv->createView()
            ]);
        } else {
            $modifyUserForm = $this->createForm(RecruiterModificationType::class, $user);
            $modifyUserForm->handleRequest($request);
            $picture = new Picture();
            $changePictureForm = $this->createForm(ChangePictureRecruiterType::class, $picture);

            $listeCauses = $causeRepository->exclusionCauses($user);

            $listeRecognitionRecruiter = $recognitionRepository->findBy(["recruiter" => $this->getUser()]);
            $recognitionForm = $this->createForm(RecognitionType::class);

            if ($modifyUserForm->isSubmitted() && $modifyUserForm->isValid()) {
                //changing carbon foot sprint
                $footPrintProofFile = $modifyUserForm->get('carbonFootPrintProof')->getData();
                if($footPrintProofFile){
                    $originalFilename = pathinfo($footPrintProofFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$footPrintProofFile->guessExtension();
                    try {
                        //saving file
                        $footPrintProofFile->move(
                            $this->getParameter('carbonFootPrintProof_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Il y a eu une erreur lors de l\'upload du fichier');
                    }

                    $alternateMail = $this->getUser()->getAlternateMail();

                    if($alternateMail != NULL) {

                        //sending email
                        $email = (new Email())
                            ->from('team@sowrs.com')
                            ->to('team@sowrs.com')
                            ->subject('Footprint proof from ' . $this->getUser()->getAlternateMail())
                            ->text('La preuve de l\'empreinte carbone')
                            ->attachFromPath('uploads/carbonFootPrintProof/' . $newFilename)
                            ->html('<p>Veuillez trouver ci-joint mon justificatif.</p><p>Cordialement</p>');

                    }else {

                        //sending email
                        $email = (new Email())
                            ->from('team@sowrs.com')
                            ->to('team@sowrs.com')
                            ->subject('Footprint proof from ' . $this->getUser()->getEmail())
                            ->text('La preuve de l\'empreinte carbone')
                            ->attachFromPath('uploads/carbonFootPrintProof/' . $newFilename)
                            ->html('<p>Veuillez trouver ci-joint mon justificatif.</p><p>Cordialement</p>');

                    }

                    $mailer->send($email);

                    $user->setCarbonFootPrintProofFilename($newFilename);
                }
                if($user instanceof Recruiter){
                    if($modifyUserForm->get('presentationVideoURL')->getData()){
                        if(!strpos($modifyUserForm->get('presentationVideoURL')->getData(), "embed")){
                            $videoURLInfo = explode("watch?v=", $modifyUserForm->get('presentationVideoURL')->getData());
                            $url = $videoURLInfo[0] . "embed/" . $videoURLInfo[1];
                            $user->setPresentationVideoURL($url);
                        }
                    }
                }
                if(!preg_match("/^[https][a-zA-Z0-9:%_+.,#?\/!@&=-]{1,256}$/", $user->getSocialNetwork()->getWebSite())){
                    $website = $user->getSocialNetwork()->getWebSite();
                    $website = "https://" . $website;
                    $user->getSocialNetwork()->setWebSite($website);
                }
                $em->persist($user);
                $em->flush();
                $this->addFlash("success", "Vos modifications ont bien été enregistrées");
                if($user->getScoring()){
                    return $this->redirectToRoute('main_dash_board', [

                    ]);
                }else{
                    return $this->redirectToRoute('dash_board_entitySenseRate', [

                    ]);
                }
            }

            return $this->render('dash_board/myDetails.html.twig', [
                'modifyUserForm' => $modifyUserForm->createView(),
                'listcauses' => $listeCauses,
                'recognitions' => $listeRecognitionRecruiter,
                'recognitionForm' => $recognitionForm->createView(),
                'user' => $user,
                'formPictureRecruiter' => $changePictureForm->createView()

            ]);
        }
    }

}
