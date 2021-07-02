<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Form\CreateCvType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CvController extends AbstractController
{

    /**
     * @Route("/user/register/cv", name="register_cv")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function registerCv(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $cv = new Cv();
        $formCv = $this->createForm(CreateCvType::class, $cv);
        $formCv->handleRequest($request);

        if ($formCv->isSubmitted() && $formCv->isValid()) {

            $user = $this->getUser();
            $cVitae = $user->getCv();

            if($cVitae){
                $user->setCv($cv);
                $em->remove($cVitae);
            }else{
                $user->setCv($cv);
            }

            $em->persist($cv);
            $em->persist($user);
            $em->flush();
            $em->refresh($user);

            return new JsonResponse(['succes' => 'Votre cv a bien été téléchargé']);
        }
        return new JsonResponse($formCv->getErrors(), 400);
    }
}
