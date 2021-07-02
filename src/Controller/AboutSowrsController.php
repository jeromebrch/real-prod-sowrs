<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutSowrsController extends AbstractController
{
    /**
     * returns the page about sowrs
     * @Route("/aboutSowrs", name="main_about_sowrs")
     * @return Response
     */
    public function aboutSowrs(): Response
    {
        //Je suis un commentaire de test de pull !
        return $this->render('main/aboutSowrs.html.twig');
    }

}
