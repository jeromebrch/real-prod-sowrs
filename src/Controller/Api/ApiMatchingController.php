<?php

namespace App\Controller\Api;

use App\Entity\Candidate;
use App\Entity\Recruiter;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiMatchingController extends AbstractController
{
    /**
     * @Route("/user/api/matching", name="api_matching")Ò
     * @param UserRepository $userRepo
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function matching(UserRepository $userRepo): Response
    {

        $client = HttpClient::create();

        $url = 'https://sowrs.green/api/match/';
        if ($this->getUser() instanceof Candidate) {
            $type = 'company';
        } else {
            $type = 'applicant';
        }

        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'zaCELgL.0imfnc8mVLWwsAawjYr4Rx-Af50DDqtlx',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'id' => $this->getUser()->getId(),
                'type' => $type,
                'result_number' => 3
            ],
        ]);
        $statusCode = $response->getStatusCode();

        if ($statusCode != 200) {
            return new JsonResponse("ko", 400);
        } else {
            $matchingArray = $response->toArray();
            //suppression de la clé "scores"
            unset($matchingArray[0]['scores']);
            unset($matchingArray[1]['scores']);
            unset($matchingArray[2]['scores']);

            //stocker les ids dans un tableau
            $ids = array();
            array_push($ids,
                31, //$matchingArray[0]["sowrs_id"],
                36, //$matchingArray[1]["sowrs_id"],
                37); //$matchingArray[2]["sowrs_id"]);

            //récuperer les utilisateurs correspondants dans un tableau
            $users = $userRepo->findBy(['id' => $ids]);

            if ($this->getUser() instanceof Recruiter) {
                //boucler sur le tableau de user
                //et vérifier à chaque passage de la boucle quel est le taux de match associé
                foreach ($users as $user) {
                    switch ($user->getId()) {
                        case $matchingArray[0]["sowrs_id"]:
                            $matchingArray[0]["first_name"] = $user->getFirstname();
                            $matchingArray[0]["last_name"] = $user->getLastname();
                            $matchingArray[0]["business_profile"] = $user->getBusinessProfile()->getWording();
                            if ($user->getPicture()) {
                                $matchingArray[0]["picture"] = $user->getPicture()->getPictureName();
                            }
                            break;
                        case $matchingArray[1]["sowrs_id"]:
                            $matchingArray[1]["first_name"] = $user->getFirstname();
                            $matchingArray[1]["last_name"] = $user->getLastname();
                            $matchingArray[1]["business_profile"] = $user->getBusinessProfile()->getWording();
                            if ($user->getPicture()) {
                                $matchingArray[1]["picture"] = $user->getPicture()->getPictureName();
                            }
                            break;
                        case $matchingArray[2]["sowrs_id"]:
                            $matchingArray[2]["first_name"] = $user->getFirstname();
                            $matchingArray[2]["last_name"] = $user->getLastname();
                            $matchingArray[2]["business_profile"] = $user->getBusinessProfile()->getWording();
                            if ($user->getPicture()) {
                                $matchingArray[2]["picture"] = $user->getPicture()->getPictureName();
                            }
                            break;
                    }
                }
            } else {
                foreach ($users as $user) {
                    switch ($user->getId()) {
                        case 31://$matchingArray[0]["sowrs_id"]:
                            $matchingArray[0]["entity_name"] = $user->getEntityName();
                            $matchingArray[0]["cause"] = $user->getMainCause()->getText();
                            if ($user->getPicture()) {
                                $matchingArray[0]["picture"] = $user->getPicture()->getPictureName();
                            }
                            break;
                        case 36: //$matchingArray[1]["sowrs_id"]:
                            $matchingArray[1]["entity_name"] = $user->getEntityName();
                            $matchingArray[1]["cause"] = $user->getMainCause()->getText();
                            if ($user->getPicture()) {
                                $matchingArray[1]["picture"] = $user->getPicture()->getPictureName();
                            }
                            break;
                        case 37: //$matchingArray[2]["sowrs_id"]:
                            $matchingArray[2]["entity_name"] = $user->getEntityName();
                            $matchingArray[2]["cause"] = $user->getMainCause()->getText();
                            if ($user->getPicture()) {
                                $matchingArray[2]["picture"] = $user->getPicture()->getPictureName();
                            }
                            break;
                    }
                }
            }
            dump($matchingArray);
            $scoring = $this->getUser()->getScoring();

            return $this->render('dash_board/senseRate/resultSensRate.html.twig', [
                'scoringUser' => $scoring,
                'matchingArray' => $matchingArray
            ]);
        }
    }
}
