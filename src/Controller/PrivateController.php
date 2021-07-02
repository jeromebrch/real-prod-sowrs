<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class PrivateController extends AbstractController
{

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
