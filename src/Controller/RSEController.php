<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RSEController extends AbstractController
{
    /**
     * @Route("/rse", name="rse")
     */
    public function index(): Response
    {
        return $this->render('rse/homeRSE.html.twig', [
            'controller_name' => 'RSEController',
        ]);
    }
}
