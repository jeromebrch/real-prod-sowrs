<?php


namespace App\Controller\Api;

use App\Entity\Scoring;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiScoreController extends AbstractController
{

    /**
     * @Route("/user/api/score", name="api_score")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function calculation(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

// on récupère les valeurs de la requête au format Json
        $params = json_decode($request->getContent());
// on crée une instance HttpClient
        $client = HttpClient::create();
// initialisation de l'URL de l'api tiers
        $url = 'https://sowrs.green/api/score/';
// appel de l'api avec les 2 entêtes nécessaires et les paramètres reçus
        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'zaCELgL.0imfnc8mVLWwsAawjYr4Rx-Af50DDqtlx',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'choices' => $params->choices,
                'id' => $params->id,
                'type' => $params->type
            ],
        ]);
// récupération du code statut
        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
// l'API n'a pas fonctionné, on retourne une erreur
            return new JsonResponse("ko", 400);
        } else {
// récupération du résultat (tableau "score")
            $resultat = $response->toArray();
            //on instancie un objet scoring
            $scoring = new Scoring();
            //le tableau de choix
            $scoring->setChoices($params->choices);

            $scoring->setSocial($resultat['score'] [0]);
            $scoring->setEnvironment($resultat['score'] [1]);
            $scoring->setEconomy($resultat['score'] [2]);
            $scoring->setSocietal($resultat['score'] [3]);
            //on a créer une fonction qui récupère la plus grande valeur
            $bigValue = $scoring->getGreatestValue($resultat);
            $scoring->setGreatestValue($bigValue);

            $user->setScoring($scoring);
            $em->persist($user);
            $em->flush();

// et retourner la réponse ok au format JSON
            return new JsonResponse($resultat, 200);
        }
    }
}