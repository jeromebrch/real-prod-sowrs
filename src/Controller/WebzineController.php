<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CensuredWordRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class WebzineController extends AbstractController
{
    /**
     * @Route("/webzine", name="home_webzine")
     * @param CommentRepository $commentRepo
     * @param PostRepository $postrepo
     * @return Response
     */
    public function homeWebzine(PostRepository $postrepo, CommentRepository $commentRepo): Response {

        $posts = $postrepo->findPublishedPost();
        $comments = $commentRepo->findAllUnpublished();

        return $this->render('webzine/homeWebzine.html.twig', [
            'posts' => $posts,
            'nbrUnpublishedComments' => count($comments)
        ]);
    }

    /**
     * @Route("/admin/createpost", name="create_post")
     * @param PostRepository $postRepo
     * @param Request $req
     * @param EntityManagerInterface $em
     * @param SluggerInterface $slugger
     * @return Response
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
     * @param EntityManagerInterface $em
     * @param Request $req
     * @param PostRepository $postRepo
     * @param CommentRepository $commentRepo
     * @param $id
     */
    public function postDetails($id, PostRepository $postRepo,CensuredWordRepository $censuredWordRepo, CommentRepository $commentRepo, EntityManagerInterface $em, Request $req){

        $user = $this->getUser();
        $post = $postRepo->find($id);
        $comments = $commentRepo->findPublishedByPost($post);
        $comment = new Comment();
        $censuredWords = $censuredWordRepo->findAll();

        $nbrVuesPost = $post->getNumberOfViews();
        $post->setNumberOfViews($nbrVuesPost + 1);
        $em->flush();

        $newestPost = $postRepo->findLatestPost();
        $moreViewsPost = $postRepo->findMoreViewsPost();

        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($req);
        if($commentForm->isSubmitted() && $commentForm->isValid()){
            //verify is the comment contains forbidden words, cf list in database
            foreach($censuredWords as $wordID => $value){
                if(!in_array($value->getWord(), explode(" ", $comment->getText())) and !in_array($value->getWord(), explode(" ", $comment->getTitle()))){
                    $validator = true;
                }else{
                    $validator = false;
                    $forbiddenWord = $value->getWord();
                    break;
                }
            }
            if($validator){
                $comment->setWriterUser($user);
                $comment->setCreationDate(new \DateTime);
                $comment->setIsOnline(false);
                $comment->setPost($post);
                $em->persist($comment);
                $em->flush();
                $this->addFlash("success", "Votre commentaire a été pris en compte et va être modéré avant publication");
            }else{
                $this->addFlash("success", "Votre commentaire contient un mot interdit (" . $forbiddenWord . "), il n'est pas valide");
            }
        }

        return $this->render('webzine/postDetail.html.twig', [
            'post' => $post,
            'newestPost' => $newestPost,
            'moreViewsPost' => $moreViewsPost,
            'commentForm' => $commentForm->createView(),
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/tag/{id}", name="show_tag", requirements={"id":"\d+"})
     * @param $id
     * @param PostRepository $postRepo
     * @param TagRepository $tagRepo
     */
    public function showTag($id, TagRepository $tagRepo, PostRepository $postRepo){

        $tag = $tagRepo->find($id);
        $posts = $postRepo->findPostsFromTag($tag);

        return $this->render('webzine/articlesFromTag.html.twig', [
            'tag' => $tag,
            'posts' => $posts
        ]);

    }

    /**
     * @Route("/admin/unpublishedpost", name="unpublished_post")
     * @param PostRepository $postRepo
     */
    public function unpublishedPost(PostRepository $postRepo){
        $posts = $postRepo->findUnpublishedPost();

        return $this->render('webzine/unpublishedPost.html.twig', [
            'unpublishedPosts' => $posts
        ]);

    }

    /**
     * @Route("/admin/publish/{id}", name="publish_post", requirements={"id":"\d+"})
     * @param $id
     * @param EntityManagerInterface $em
     * @param PostRepository $postrepo
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
     * @param EntityManagerInterface $em
     * @param $id
     * @param PostRepository $postRepo
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
     * @param PostRepository $postRepo
     * @param $id
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function hidePost($id, PostRepository $postRepo, EntityManagerInterface $em){
        $post = $postRepo->find($id);
        $post->setIsPublished(false);
        $em->flush();

        $this->addFlash('success', 'L\'article a bien été masqué');

        return $this->redirectToRoute('home_webzine', [

        ]);
    }

    /**
     * @Route("/tag", name="add_tag")
     * @param EntityManagerInterface $em
     * @param TagRepository $tagRepo
     * @param Request $req
     * @return JsonResponse
     */
    public function addTag(Request $req, EntityManagerInterface $em, TagRepository $tagRepo) :JsonResponse {

        $tags[] = json_decode($req->getContent('tag'), true);
        $tag = $tags[0]['tag'];
        $newTag = new Tag();
        $tagsValue[] = [];

        if($tag){
            // get all of the existing tags in database
            $tagCollection = $tagRepo->findAll();
            // get all of the existing tags values, push in an array
            foreach($tagCollection as $existingTag){
                array_push($tagsValue, strtolower($existingTag->getText()));
            }
            // verify if the tag write by the admin is in database or not, persist if not
            if(!in_array(strtolower($tag), $tagsValue)){
                $newTag->setText(ucwords($tag));
                $em->persist($newTag);
                $em->flush();
            }
            return new JsonResponse([
                'success' => true
            ]);
        }else{
            return new JsonResponse([
                'success' => false
            ]);
        }
    }

    /**
     * @Route("/admin/comments", name="waiting_comments")
     * @param CommentRepository $commentRepo
     */
    public function waitingComments(CommentRepository $commentRepo){

        //get all the waiting comments
        $comments = $commentRepo->findAllUnpublished();

        return $this->render('webzine/unpublishedComments.html.twig', [
            'unpublishedComments' => $comments
        ]);
    }

    /**
     * @Route("/admin/deletecomment/{id}", name="delete_comment", requirements={"id"="\d+"})
     * @param CommentRepository $commentRepo
     * @param EntityManagerInterface $em
     * @param $id
     */
    public function deleteComment($id, EntityManagerInterface $em, CommentRepository $commentRepo){
        $comment = $commentRepo->find($id);
        $em->remove($comment);
        $em->flush();

        $this->addFlash("success", "Le commentaire a bien été supprimé");

        return $this->redirectToRoute('waiting_comments', [

        ]);
    }

    /**
     * @Route("/admin/validatecomment/{id}", name="validate_comment", requirements={"id"="\d+"})
     * @param $id
     * @param EntityManagerInterface $em
     * @param CommentRepository $commentRepo
     */
    public function validateComment($id, EntityManagerInterface $em, CommentRepository $commentRepo){
        $comment = $commentRepo->find($id);
        $comment->setIsOnline(true);
        $em->flush();

        $this->addFlash("success", "Le commentaire a bien été validé");

        return $this->redirectToRoute('waiting_comments', [

        ]);
    }

}
