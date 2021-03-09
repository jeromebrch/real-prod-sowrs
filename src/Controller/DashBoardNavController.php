<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\DashBoardNavType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashBoardNavController extends AbstractController
{
    /**
     * @Route("/user/dash/board/nav", name="dash_board_nav")
     * @return Response
     */
    public function navBar(): Response
    {
        $picture = new Picture();
        $formPicture = $this->createForm(DashBoardNavType::class, $picture);

        return $this->render('nav/dashBoardNav.html.twig', [
            'formPicture' => $formPicture->createView(),
        ]);
    }

    /**
     * @Route("/user/register/picture", name="register_picture")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function registerPicture(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $picture = new Picture();
        $formPicture = $this->createForm(DashBoardNavType::class, $picture);
        $formPicture->handleRequest($request);

        if ($formPicture->isSubmitted() && $formPicture->isValid()) {

            $user = $this->getUser();
            $user->setPicture($picture);

            $em->persist($picture);
            $em->persist($user);
            $em->flush();
            $em->refresh($user);

            return new JsonResponse(['pictureName' => $picture->getPictureName()]);
        }
        return new JsonResponse($formPicture->getErrors(), 400);
    }

    /**
     * puts the account in private
     * @Route("/user/account/status", name="dash_board_account_status")
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function privatePublic(EntityManagerInterface $em): RedirectResponse
    {
        if ($this->getUser()->getPrivate() == false) {
            $this->getUser()->setPrivate(true);
            $em->persist($this->getUser());
            $em->flush();
            $this->addFlash('success', 'Vous êtes maintenant en mode privé');
        } else {
            $this->getUser()->setPrivate(false);
            $em->persist($this->getUser());
            $em->flush();
            $this->addFlash('success', 'Heureux de vous revoir en public');
        }

        return $this->redirectToRoute('main_dash_board');
    }
}
