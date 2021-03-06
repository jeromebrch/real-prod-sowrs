<?php

namespace App\Controller;

use App\Repository\JobOfferRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    /**
     * GENERATE THE SITEMAP FOR GOOGLE BOTS
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     * @param Request $req
     * @param PostRepository $postRepo
     * @return Response
     */
    public function index(Request $req, PostRepository $postRepo, JobOfferRepository $offerRepo): Response {

        $hostname = $req->getSchemeAndHttpHost();

        $urls = [];

        //Define the website urls
        $urls[] = ['loc' => $this->generateUrl('main')];
        $urls[] = ['loc' => $this->generateUrl('app_register')];
        $urls[] = ['loc' => $this->generateUrl('app_login')];
        $urls[] = ['loc' => $this->generateUrl('main_about_sowrs')];
        $urls[] = ['loc' => $this->generateUrl('rse')];
        $urls[] = ['loc' => $this->generateUrl('main_contact_us')];
        $urls[] = ['loc' => $this->generateUrl('main_job_offers_list')];
        $urls[] = ['loc' => $this->generateUrl('main_candidate_list')];
        $urls[] = ['loc' => $this->generateUrl('home_webzine')];
        $urls[] = ['loc' => $this->generateUrl('app_logout')];

        //Define the dynamic urls
        foreach($postRepo->findAll() as $post){
            $image = [
                'loc' => '/uploads/illustrations/'.$post->getPictureFilename(), // URL to image
                'title' => $post->getTitle()    // Post title
            ];
            $urls[] = [
                'priority' => 0.9,
                'loc' => "/postdetail/" . $post->getId(), //Define the post url
                'lastmod' => $post->getCreationDate()->format('Y-m-d'),
                'image' => $image
            ];
        }
        foreach($offerRepo->findAll() as $offer){
            $urls[] = [
                'priority' => 0.9,
                'loc' => "/jobOffer/show/" . $offer->getId(), //Define the offer url
            ];
        }

        // Making the XML response
        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname
            ]),
            200
        );
        // Set header
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
