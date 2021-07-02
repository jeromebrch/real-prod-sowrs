<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagingController extends AbstractController
{
    /**
     * View to the mesaging page
     *
     * @Route("/messaging/messaging_page", name="messaging")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function seeMessaging(EntityManagerInterface $em): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        //getting sended messages
        $messages = $this->getDoctrine()->getRepository(Message::class)->findByUserRecipient($user);
        //counting unreaded messages
        $messageState = $em->getRepository(Message::class)->count(['userRecipient' => $user, 'state' => 'non lu']);
        $nonlu = 'non lu';
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();


        return $this->render('messaging/messaging_page.html.twig', [
            'controller_name' => 'MessagingController',
            'messages' => $messages,
            'category' => $category,
            'nonlus' => $messageState,
            'nonlu' => $nonlu

        ]);
    }

    /**
     * Read a message received
     *
     * @Route("/messaging/readMessage/{id}", name="read_message")
     * @param EntityManagerInterface $em
     * @param $id
     * @return Response
     */
    public function readMessage($id, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        //counting unreaded messages
        $messageState = $em->getRepository(Message::class)->count(['userRecipient' => $user, 'state' => 'non lu']);
        //getting the message to read
        $message = $this->getDoctrine()->getRepository(Message::class)->find($id);
        //setting the message state in readed
        $message->setState('lu');
        $em->persist($message);
        $em->flush();


        return $this->render('messaging/read_message.html.twig', [
            'controller_name' => 'MessagingController',
            'message' => $message,
            'nonlu' => $messageState
        ]);
    }


    /**
     * Erase received message
     *
     * @Route("/messaging/delete/{id}", name="delete_message")
     * @param $id
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function DeleteMessage($id, EntityManagerInterface $em): Response
    {
        $message = $this->getDoctrine()->getRepository(Message::class)->find($id);
        if ($message) {
            $em->remove($message);
            $em->flush();

            $this->addFlash('success', 'Votre message a été supprimé');

            return $this->redirectToRoute('messaging');
        } else {
            return $this->render('error/notFound.html.twig');

        }
    }
}
