<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class WebzineController extends AbstractController
{
    /**
     * @Route("/webzine", name="home_webzine")
     */
    public function homeWebzine(PostRepository $postrepo): Response {

        $posts = $postrepo->findPublishedPost();

        return $this->render('webzine/homeWebzine.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/admin/createpost", name="create_post")
     */
    public function createPost(Request $req, EntityManagerInterface $em, SluggerInterface $slugger, PostRepository $postRepo){

        $post = new Post();
        $postForm = $this->createForm(PostType::class, $post);
        $postForm->handleRequest($req);
        if($postForm->isSubmitted() && $postForm->isValid()){
            // upload the post illustration
            $pictureFile = $postForm->get('picture')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();
                try {
                    $pictureFile->move(
                        $this->getParameter('post_illus_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->render('bundles/TwigBundle/Exception/error500.html.twig', [

                    ]);
                }
                $post->setPictureFilename($newFilename);
            }

            $post->setCreationDate(new \DateTime());
            $post->setIsPublished(false);

            $em->persist($post);
            $em->flush();

            $posts = $postRepo->findUnpublishedPost();

            return $this->render('webzine/unpublishedPost.html.twig', [
                'unpublishedPosts' => $posts
            ]);

        }
        return $this->render('webzine/createPost.html.twig', [
            'postForm' => $postForm->createView()
        ]);
    }

    /**
     * @Route("/postdetail/{id}", name="post_details", requirements={"id":"\d+"})
     */
    public function postDetails($id, PostRepository $postRepo){

        $post = $postRepo->find($id);
        $newestPost = $postRepo->findLatestPost();

        return $this->render('webzine/postDetail.html.twig', [
            'post' => $post,
            'newestPost' => $newestPost
        ]);

    }

    /**
     * @Route("/admin/unpublishedpost", name="unpublished_post")
     */
    public function unpublishedPost(PostRepository $postRepo){
        $posts = $postRepo->findUnpublishedPost();

        return $this->render('webzine/unpublishedPost.html.twig', [
            'unpublishedPosts' => $posts
        ]);

    }

    /**
     * @Route("/admin/publish/{id}", name="publish_post", requirements={"id":"\d+"})
     */
    public function publishPost($id, PostRepository $postrepo, EntityManagerInterface $em){

        $post = $postrepo->find($id);
        $post->setIsPublished(true);
        $em->flush();

        $this->addFlash('success', 'L\'article a bien été publié');

        return $this->redirectToRoute('home_webzine', [

        ]);

    }

    /**
     * @Route("/admin/delete/{id}", name="delete_post", requirements={"id":"\d+"})
     */
    public function deletePost($id, EntityManagerInterface $em, PostRepository $postRepo){

        $post = $postRepo->find($id);
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'L\'article a bien été supprimé');

        return $this->redirectToRoute('home_webzine', [

        ]);
    }

    /**
     * @Route("/admin/hide/{id}", name="hide_post", requirements={"id":"\d+"})
     */
    public function hidePost($id, PostRepository $postRepo, EntityManagerInterface $em){
        $post = $postRepo->find($id);
        $post->setIsPublished(false);
        $em->flush();

        $this->addFlash('success', 'L\'article a bien été masqué');

        return $this->redirectToRoute('home_webzine', [

        ]);
    }

}
