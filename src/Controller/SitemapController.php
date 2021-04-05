<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(Request $req, PostRepository $postRepo): Response {

        $hostname = $req->getSchemeAndHttpHost();

        $urls = [];

        //On défini les url statiques
        $urls[] = ['loc' => $this->generateUrl('main')];
        $urls[] = ['loc' => $this->generateUrl('app_register')];
        $urls[] = ['loc' => $this->generateUrl('app_login')];
        $urls[] = ['loc' => $this->generateUrl('main_about_sowrs')];
        $urls[] = ['loc' => $this->generateUrl('main_contact_us')];
        $urls[] = ['loc' => $this->generateUrl('main_job_offers_list')];
        $urls[] = ['loc' => $this->generateUrl('main_candidate_list')];
        $urls[] = ['loc' => $this->generateUrl('home_webzine')];
        $urls[] = ['loc' => $this->generateUrl('app_login')];
        $urls[] = ['loc' => $this->generateUrl('app_logout')];

        //On génère les url dynamique des articles
        foreach($postRepo->findAll() as $post){
            $image = [
                'loc' => '/uploads/illustrations/'.$post->getPictureFilename(), // URL to image
                'title' => $post->getTitle()    // Post title
            ];
            $urls[] = [
                'priority' => 0.9,
                'changefreq' => "monthly",
                'loc' => "/sowrs/postdetail/" . $post->getId(), //Define the post url
                'lastmod' => $post->getCreationDate()->format('Y-m-d'),
                'image' => $image
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
