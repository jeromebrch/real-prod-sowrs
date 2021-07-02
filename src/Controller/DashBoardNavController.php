<?php
namespace App\Controller;

use App\Entity\Cv;
use App\Entity\Picture;
use App\Form\CreateCvType;
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
    //To display side navbar
    /**
     * @Route("/user/dash/board/nav", name="dash_board_nav")
     * @return Response
     */
    public function navBar(): Response
    {
        $user = $this->getUser();
        $picture = new Picture();
        $formPicture = $this->createForm(DashBoardNavType::class, $picture);

        $cv = new Cv();
        $formCv = $this->createForm(CreateCvType::class, $cv);

        $messages = $user->getReceivedMessages();
        $nonLu = 'non lu';
        $count= 0;

        foreach ($messages as $message) {
            if ($message->getState() === $nonLu) {
                $count++;
            }
        }

        return $this->render('nav/dashBoardNav.html.twig', [
            'formPicture' => $formPicture->createView(),
            'formCv' => $formCv->createView(),
            'nonlu' => $count
        ]);
    }

}

