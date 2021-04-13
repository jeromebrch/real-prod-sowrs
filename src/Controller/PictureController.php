<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\DashBoardNavType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{

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
            $pict = $user->getPicture();

            if($pict){
                $user->setPicture($picture);
                $em->remove($pict);
            }else{
                $user->setPicture($picture);
            }

            $em->persist($picture);
            $em->persist($user);
            $em->flush();
            $em->refresh($user);

            return new JsonResponse(['pictureName' => $picture->getPictureName()]);
        }
        return new JsonResponse($formPicture->getErrors(), 400);
    }

}
