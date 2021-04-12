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
     * @Route("/messaging/messaging_page", name="messaging")
     */
    public function seeMessaging(EntityManagerInterface $em): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        //getting sended messages
        $messageRepo = $this->getDoctrine()->getRepository(Message::class);
        $messages = $messageRepo->findByUserRecipient($user);
        //counting unreaded messages
        $messageState = $em->getRepository(Message::class)->count(['userRecipient' => $user, 'state' => 'non lu']);
        $nonlu = 'non lu';
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepo->findAll();

        return $this->render('messaging/messaging_page.html.twig', [
            'controller_name' => 'MessagingController',
            'messages' => $messages,
            'category' => $category,
            'nonlus' => $messageState,
            'nonlu' => $nonlu

        ]);
    }

    /**
     * @Route("/messaging/readMessage/{id}", name="read_message")
     */
    public function readMessage($id, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        //counting unreaded messages
        $messageState = $em->getRepository(Message::class)->count(['userRecipient' => $user, 'state' => 'non lu']);
        //getting the message to read
        $messageRepo = $this->getDoctrine()->getRepository(Message::class);
        $message = $messageRepo->find($id);
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
     * @Route("/messaging/delete/{id}", name="delete_message")
     */
    public function DeleteMessage($id, EntityManagerInterface $em): Response
    {
        $messageRepo = $this->getDoctrine()->getRepository(Message::class);
        $message = $messageRepo->find($id);
        if ($message) {
            $em->remove($message);
            $em->flush();

            $this->addFlash('success', 'Votre message a été supprimé');

            return $this->redirectToRoute('messaging');
        } else {
            return $this->render('error/notFound.html.twig');

        }
    }

    /**
     * @Route("/messaging/categoryMessage", name="category_message")
     */
    /* public function getCategoryMessage(Request $request): JsonResponse
     {  //méthode to sort messages from categories
         dd($request->request);
         return new JsonResponse(['cat' => 's']);
     }
     {% block javascripts %}
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
                 integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
                 crossorigin="anonymous"></script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.5.4/umd/popper.min.js"></script>

     <script>

         var select = document.querySelector('#category');

         select.addEventListener('change', function (e) {
             var option = e.target[e.target.selectedIndex];
             var idCategory = option.value;
             var payload = {id:idCategory, a: 'demo'};

             var r = fetch('{{ path('category_message') }}',
                 {
                     method: 'POST',
                     body: JSON.stringify(payload),
                     headers : {'Content-type': 'application/json'}
                 })
                 .then(response => console.log(response.json()))
         });

     </script>

     <div class="col-lg-12 ">
                             <label for="category">Filtrer: </label>
                             <select id="category" class="btn required" style="width: 250px; margin: 20px; background-color: white"  name="nameCat">
                                {% for categor in category %}
                                     <option value="{{ categor.id }}">{{ categor.name }}</option>
                                 {% endfor %}
                             </select>
                     </div>
 {% endblock %}
 */
}
