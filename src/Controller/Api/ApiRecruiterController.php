<?php


namespace App\Controller\Api;


use App\Entity\Recognition;
use App\Form\RecognitionType;
use App\Repository\RecognitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiRecruiterController extends AbstractController
{

    /**
     * @Route("/recruiter/api/recruiter/recognition/add", name="api_recruiter_action_add", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function addRecognition(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        try {
            // on récupère le JSON
            $data = json_decode($request->getContent(), true);
            // on initialise une nouvelle action du recruteur
            $recognition = new Recognition();
            // on crée un formulaire
            $form = $this->createForm(RecognitionType::class, $recognition);
            // on simule le submit du formulaire avec les données reçues
            $form->submit($data, false);
            if ($form->isValid()) {
                // si le formulaire est valide, on persiste l'action du recruteur
                $em->persist($recognition);
                $em->flush();
                // on retourne l'objet crée en JSON avec statut HTTP 201
                return new JsonResponse(
                    [
                        'data' => json_decode($serializer->serialize($recognition, 'json',
                            ['groups' => 'get_add_action_recruiter'])),
                        'message' => 'Cause créée',
                        'status' => Response::HTTP_CREATED
                    ],
                    Response::HTTP_CREATED);
            }
            // si formulaire non valide, on retourne une exception
            throw new Exception((string)$form->getErrors(true, false));
        } catch (Exception $ex) {
            // on retourne une erreur HTTP 400
            return new JsonResponse(['Data' => null, 'message' => $ex->getMessage(), 'status' => Response::HTTP_BAD_REQUEST],
                Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/recruiter/api/recruiter/recognition/delete", name="api_recruiter_action_delete", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param RecognitionRepository $recognitionRepo
     * @return JsonResponse
     */
    public function deleteRecognition(Request $request, EntityManagerInterface $em, RecognitionRepository $recognitionRepo): JsonResponse
    {
        $data = json_decode($request->getContent());

        try {
            $recognition = $recognitionRepo->find($data->id_cause);
            $em->remove($recognition);
            $em->flush();
            return new JsonResponse(
                [
                    'data' => $data,
                    'message' => 'Cause supprimée',
                    'status' => Response::HTTP_OK
                ],
                Response::HTTP_CREATED);
        } catch (Exception $e) {

            return new JsonResponse(
                [
                    'data' => $data,
                    'message' => 'Cause non supprimée',
                    'status' => Response::HTTP_BAD_REQUEST
                ],
                Response::HTTP_BAD_REQUEST);
        }

    }
}