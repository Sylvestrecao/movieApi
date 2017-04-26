<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
class UserController extends Controller
{
    /**
     * @Route("/{movie_id}/add_comment", name="add_comment", requirements={"movie_id": "\d+"})
     * @Method("POST")
     */
    public function addCommentAction(Request $request, $movie_id)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = new Comment();
        
        $form = $this->createForm(CommentType::class, $comment, array(
            'action' => $this->generateUrl('add_comment', array("movie_id" => $movie_id)),
            'method' => 'POST'
        ));

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $comment->setUser($this->getUser());
            $comment->setMovieId($movie_id);

            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Votre commentaire a été ajouté avec succès !');

            return $this->redirectToRoute('movie_details', array('movie_id' => $movie_id, '_fragment' => 'commentSection'));
        }

        return $this->render('AppBundle:User:add-comment.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{movie_id}/add_comment/{comment_parent_id}", name="add_comment_response", requirements={"movie_id": "\d+", "comment_parent_id": "\d+"})
     * @Method("POST")
     */
    public function addCommentResponseAction(Request $request, $movie_id, $comment_parent_id)
    {
        $em = $this->getDoctrine()->getManager();
        $commentParent = $em->getRepository('AppBundle:Comment')->find($comment_parent_id);
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, array(
            'action' => $this->generateUrl('add_comment_response', array("movie_id" => $movie_id, "comment_parent_id" => $comment_parent_id)),
            'method' => 'POST'
        ));

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $comment->setUser($this->getUser());
            $comment->setMovieId($movie_id);
            $comment->setParent($commentParent);

            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Votre commentaire a été ajouté avec succès !');

            return $this->redirectToRoute('movie_details', array('movie_id' => $movie_id, '_fragment' => 'commentSection'));
        }

        return $this->render('AppBundle:User:add-comment.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function recursiveCommentAction(Comment $comment, $depth)
    {
        return $this->render('AppBundle:User:recursive-comment.html.twig', array(
            'comment' => $comment,
            'depth' => $depth
        ));
    }

    /**
     * Add favorite movie
     *
     * @Route("/add/favorite-movie", options={"expose"=true}, name="user_add_favorite_movie")
     * @Method("POST")
     */
    public function addFavoriteMovieAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $data = $request->request->all();
            $insertMoviePlaylist = $this->container->get('insert_movie_user_playlist');
            $state = $insertMoviePlaylist->insertFavoriteMovie($user, $data);
        }

        return new JsonResponse(array("state" => $state));
    }

    /**
     * Remove favorite movie
     *
     * @Route("/remove/favorite-movie", options={"expose"=true}, name="user_remove_favorite_movie")
     * @Method("POST")
     */
    public function removeFavoriteMovieAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $data = $request->request->all();
            $movieUserInDatabase = $em->getRepository('AppBundle:MovieUser')->findMovieUserInDatabase($user->getId(), $data['Movie_Id']);
            $movieUserInDatabase->setFavoriteMovie(false);

            $em->persist($movieUserInDatabase);
            $em->flush();
        }

        return new Response("success");
    }
    /**
     * Add movie to watch
     *
     * @Route("/add/movie-to-watch", options={"expose"=true}, name="user_add_movie_to_watch")
     * @Method("POST")
     */
    public function addMovieToWatchAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $data = $request->request->all();
            $insertMoviePlaylist = $this->container->get('insert_movie_user_playlist');
            $state = $insertMoviePlaylist->insertMovieToWatch($user, $data);
        }

        return new JsonResponse(array("state" => $state));
    }

    /**
     * Remove movie to watch
     *
     * @Route("/remove/movie-to-watch", options={"expose"=true}, name="user_remove_movie_to_watch")
     * @Method("POST")
     */
    public function removeMovieToWatchAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $data = $request->request->all();
            $movieUserInDatabase = $em->getRepository('AppBundle:MovieUser')->findMovieUserInDatabase($user->getId(), $data['Movie_Id']);
            $movieUserInDatabase->setMovieToWatch(false);

            $em->persist($movieUserInDatabase);
            $em->flush();
        }

        return new Response("success");
    }
    /**
     * @Route("/add/like-comment", options={"expose"=true}, name="add_like_comment")
     * @Method("POST")
     */
    public function addLikeOnCommentAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $data = $request->request->all();
            $id = $data['Comment_Id'];
            $likeCommentManager = $this->container->get('like_comment_manager');
            $addLikeData = $likeCommentManager->addLike($id);
        }
        return new JsonResponse($addLikeData);
    }

    /**
     * @Route("/add/dislike-comment", options={"expose"=true}, name="add_dislike_comment")
     * @Method("POST")
     */
    public function addDislikeOnCommentAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $data = $request->request->all();
            $id = $data['Comment_Id'];

            $likeCommentManager = $this->container->get('like_comment_manager');
            $addDislikeData = $likeCommentManager->addDislike($id);
        }
        return new JsonResponse($addDislikeData);
    }
}
