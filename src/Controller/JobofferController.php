<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Form\ApplyType;
use App\Form\JobOfferType;
use App\Repository\JobOfferRepository;
use App\Repository\RecruiterRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobofferController extends AbstractController
{
    /**
     * returns the job offer creation form
     * @Route("/recruiter/jobOffer/create", name="dash_board_job_offer_create")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function creationJobOffer(Request $request, EntityManagerInterface $em): Response
    {
        //on instancie une nouvelle offre
        $offer = new JobOffer();
        //on la place dans le formulaire
        $jobOfferForm = $this->createForm(JobOfferType::class, $offer);
        $jobOfferForm->handleRequest($request);

        //quand on aura le retour on vérifie si il est valid et on le pousse en bdd
        if ($jobOfferForm->isSubmitted() && $jobOfferForm->isValid()) {
            //une offre possede un recruteur
            //on lui met le user en cours
            $offer->setEntity($this->getUser());
            $em->persist($offer);
            $em->flush();

            $this->addFlash('success', 'Votre offre a bien été publiée');

            return $this->redirectToRoute('dash_board_my_offers');
        }

        return $this->render('dash_board/jobOffer/jobOfferCreation.html.twig', [
            'jobOfferForm' => $jobOfferForm->createView(),
        ]);
    }

    //Méthode de modification des offres d'emploi pour un recruteur

    /**
     * pauses or play an offer
     * @Route("/recruiter/jobOffer/pauseAndPlayOffer/{id}", name="jobOffer_pauseAndPlayOffer",requirements={"id":"\d+"})
     * @param $id
     * @param JobOfferRepository $repositoryJobOffer
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function pauseAndPlayOffer($id, JobOfferRepository $repositoryJobOffer, EntityManagerInterface $entityManager): Response
    {
        $jobOffer = $repositoryJobOffer->find($id);

        if ($jobOffer) {
            if ($jobOffer->getPublished() === true) {
                $jobOffer->setPublished(false);
                $entityManager->flush();

                $this->addFlash('success', 'Votre offre est mise en pause');
            } else if ($jobOffer->getPublished() === false) {
                $jobOffer->setPublished(true);
                $entityManager->flush();

                $this->addFlash('success', 'Votre offre est mise en ligne');
            }
        } else {
            return $this->render('error/notFound.html.twig');
        }
        return $this->redirectToRoute('dash_board_my_offers');
    }

    /**
     * updates the date of an offer
     * @Route("/recruiter/jobOffer/refreshOffer/{id}", name="jobOffer_refreshOffer",requirements={"id":"\d+"}))
     * @param $id
     * @param JobOfferRepository $repositoryJobOffer
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function refreshOffer($id, JobOfferRepository $repositoryJobOffer, EntityManagerInterface $entityManager): Response
    {

        $jobOffer = $repositoryJobOffer->find($id);
        if ($jobOffer) {
            $jobOffer->setCreationDate(new dateTime);
            $entityManager->flush();

            $this->addFlash('success', 'Votre offre est mise à jour');

            return $this->redirectToRoute('dash_board_my_offers');
        } else {
            return $this->render('error/notFound.html.twig');

        }
    }

    /**
     * displays the offer amendment form
     * @Route("/recruiter/jobOffer/modifyOffer/{id}", name="jobOffer_modifyJobOffer", requirements={"id":"\d+"}))
     * @param $id
     * @param JobOfferRepository $repositoryJobOffer
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function modifyOffer($id, JobOfferRepository $repositoryJobOffer,
                                EntityManagerInterface $entityManager, Request $request): Response
    {

        $jobOffer = $repositoryJobOffer->find($id);
        if ($jobOffer) {
            $jobOfferForm = $this->createForm(JobOfferType::class, $jobOffer);
            $jobOfferForm->handleRequest($request);

            if ($jobOfferForm->isSubmitted() && $jobOfferForm->isValid()) {
                $entityManager->persist($jobOffer);
                $entityManager->flush();

                $this->addFlash('success', 'Votre offre a été modifiée');

                return $this->redirectToRoute('dash_board_my_offers');
            }
        } else {
            return $this->render('error/notFound.html.twig');
        }

        return $this->render('/dash_board/jobOffer/modifyJobOffer.html.twig', [
            'jobOfferForm' => $jobOfferForm->createView(),
        ]);
    }

    /**
     * delete an offer
     * @Route("/recruiter/jobOffer/deleteOffer/{id}", name="jobOffer_deleteOffer", requirements={"id":"\d+"})
     * @param $id
     * @param JobOfferRepository $repositoryJobOffer
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function deleteOffer($id, JobOfferRepository $repositoryJobOffer, EntityManagerInterface $entityManager): Response
    {

        $jobOffer = $repositoryJobOffer->find($id);
        if ($jobOffer) {
            $entityManager->remove($jobOffer);
            $entityManager->flush();

            $this->addFlash('success', 'Votre offre a été supprimée');

            return $this->redirectToRoute('dash_board_my_offers');
        } else {
            return $this->render('error/notFound.html.twig');

        }
    }

    /**
     * @param $id
     * @param JobOfferRepository $jobOfferRepo
     * @param RecruiterRepository $recruiterRepo
     * @return Response
     * @Route("/jobOffer/show/{id}", name="jobOffer_show", requirements={"id":"\d+"})
     */
    public function showJobOffer($id, JobOfferRepository $jobOfferRepo, RecruiterRepository $recruiterRepo): Response
    {
        $jobOffer = $jobOfferRepo->find($id);

        if ($jobOffer) {
            $entity = $recruiterRepo->find($jobOffer->getEntity()->getId());
            $applyForm = $this->createForm(ApplyType::class);

            return $this->render('candidate/showJobOffer.html.twig', [
                'jobOffer' => $jobOffer,
                'entity' => $entity,
                'applyForm' => $applyForm->createView()
            ]);
        } else {
            return $this->render('error/notFound.html.twig');
        }
    }
}
