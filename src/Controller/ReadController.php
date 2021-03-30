<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadController extends AbstractController
{
    /**
     * @Route("/read", name="read")
     */
    public function countMessageReaded(): Response
    {

        $user = $this->getUser();
        $messages = $user->getMessages();
        //compte les messages non lus
        $nonLu = 'non lu';
        $count = 0;
        foreach ($messages as $message) {
            if ($message->getState() === $nonLu) {
                $count++;
            }
        }

        return $this->render('read/readedMessage.html.twig', [
            'controller_name' => 'ReadController',
            'nonlu' => $count
        ]);

    }
}