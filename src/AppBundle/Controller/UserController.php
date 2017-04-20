<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\MovieUser;
use AppBundle\Form\CommentType;
use AppBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
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
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $data = $request->request->all();
            $movieInDatabase = $em->getRepository('AppBundle:Movie')->findMovieInDatabase($data['Movie_Id']);
            if(isset($movieInDatabase)){
                $movieUserInDatabase = $em->getRepository('AppBundle:MovieUser')->findMovieUserInDatabase($user->getId(), $movieInDatabase->getId());
                if(isset($movieUserInDatabase))
                    $favoriteMovie = $movieUserInDatabase->getFavoriteMovie();
            }
            // If movie is in database and movieUser does not exist, only add new movieUser
            if(isset($movieInDatabase) && empty($movieUserInDatabase)){
                $movieUser = new MovieUser();
                $movieUser->setFavoriteMovie(true);
                $movieUser->setUser($user);
                $movieUser->setMovie($movieInDatabase);

                $em->persist($movieUser);
            }
            elseif(isset($movieInDatabase) && isset($favoriteMovie)){
                return new JsonResponse(array("state" => "error"));
            }
            elseif(isset($movieInDatabase) && $favoriteMovie == null){
                $movieUserInDatabase->setFavoriteMovie(true);
            }
            else{
                $movie = new Movie();
                $movie->setMovieDbId($data['Movie_Id']);
                $movie->setTitle($data['Movie_Title']);
                $movie->setPosterPath($data['Poster_Path']);

                $movieUser = new MovieUser();
                $movieUser->setFavoriteMovie(true);
                $movieUser->setUser($user);
                $movieUser->setMovie($movie);

                $em->persist($movieUser);
            }
            $em->flush();
        }

        return new JsonResponse(array("state" => "success"));
    }

    /**
     * Add favorite movie
     *
     * @Route("/add/movie-to-watch", options={"expose"=true}, name="user_add_movie_to_watch")
     * @Method("POST")
     */
    public function addMovieToWatchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $data = $request->request->all();
            $movieInDatabase = $em->getRepository('AppBundle:Movie')->findMovieInDatabase($data['Movie_Id']);
            if(isset($movieInDatabase)){
                $movieUserInDatabase = $em->getRepository('AppBundle:MovieUser')->findMovieUserInDatabase($user->getId(), $movieInDatabase->getId());
                if(isset($movieUserInDatabase))
                    $movieToWatch = $movieUserInDatabase->getMovieToWatch();
            }
            // If movie is in database and movieUser does not exist, only add new movieUser
            if(isset($movieInDatabase) && empty($movieUserInDatabase)){
                $movieUser = new MovieUser();
                $movieUser->setMovieToWatch(true);
                $movieUser->setUser($user);
                $movieUser->setMovie($movieInDatabase);

                $em->persist($movieUser);
            }
            elseif(isset($movieInDatabase) && isset($movieToWatch)){
                return new JsonResponse(array("state" => "error"));
            }
            elseif(isset($movieInDatabase) && $movieToWatch == null){
                $movieUserInDatabase->setMovieToWatch(true);
            }
            else{
                $movie = new Movie();
                $movie->setMovieDbId($data['Movie_Id']);
                $movie->setTitle($data['Movie_Title']);
                $movie->setPosterPath($data['Poster_Path']);

                $movieUser = new MovieUser();
                $movieUser->setMovieToWatch(true);
                $movieUser->setUser($user);
                $movieUser->setMovie($movie);

                $em->persist($movieUser);
            }
            $em->flush();
        }

        return new JsonResponse(array("state" => "success"));
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
            $em = $this->getDoctrine()->getManager();
            $comment = $em->getRepository('AppBundle:Comment')->find($id);
            $likeNumber = $comment->getCommentLike();
            $username = $comment->getUser()->getUsername();
        }

        $getCookies = $request->cookies->get('intothemovieCookie');
        if(isset($getCookies)){
            $getCookiesArray = json_decode($getCookies, true);
            if(array_key_exists("comment".$id, $getCookiesArray)){
               return new JsonResponse(array('message' => 'Vous ne pouvez pas like ou dislike un même commentaire', 'likeNumber' => $likeNumber, 'class' => 'warning'));
            }
            else{
                $newLikeNumber = $likeNumber + 1;
                $comment->setCommentLike($newLikeNumber);
                $em->persist($comment);
                $em->flush();

                $getCookiesArray["comment".$id] = 1;
                $cookieJson = json_encode($getCookiesArray);
                $cookie = new Cookie('intothemovieCookie', $cookieJson, strtotime("+1 year"));
                $response = new Response();
                $response->headers->setCookie($cookie);
                $response->send();
                return new JsonResponse(array('message' => 'Vous venez de like le commentaire de : '. $username, 'likeNumber' => $newLikeNumber, 'class' => 'success'));
            }
        }
        else{
            $newLikeNumber = $likeNumber + 1;
            $comment->setCommentLike($newLikeNumber);
            $em->persist($comment);
            $em->flush();

            $setCookieArray = array("comment".$id => 1);
            $cookieJson = json_encode($setCookieArray);
            $cookie = new Cookie('intothemovieCookie', $cookieJson, strtotime("+1 year"));
            $response = new Response();
            $response->headers->setCookie($cookie);
            $response->send();
            return new JsonResponse(array('message' => 'Vous venez de like le commentaire de : '. $username, 'likeNumber' => $newLikeNumber, 'class' => 'success'));
        }
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
            $em = $this->getDoctrine()->getManager();
            $comment = $em->getRepository('AppBundle:Comment')->find($id);
            $dislikeNumber = $comment->getCommentDislike();
            $username = $comment->getUser()->getUsername();
        }

        $getCookies = $request->cookies->get('intothemovieCookie');
        if(isset($getCookies)){
            $getCookiesArray = json_decode($getCookies, true);
            if(array_key_exists("comment".$id, $getCookiesArray)){
                return new JsonResponse(array('message' => 'Vous ne pouvez pas like ou dislike un même commentaire', 'dislikeNumber' => $dislikeNumber, 'class' => 'warning'));
            }
            else{
                $newDislikeNumber = $dislikeNumber + 1;
                $comment->setCommentDislike($newDislikeNumber);
                $em->persist($comment);
                $em->flush();

                $getCookiesArray["comment".$id] = 1;
                $cookieJson = json_encode($getCookiesArray);
                $cookie = new Cookie('intothemovieCookie', $cookieJson, strtotime("+1 year"));
                $response = new Response();
                $response->headers->setCookie($cookie);
                $response->send();
                return new JsonResponse(array('message' => 'Vous venez de dislike le commentaire de : '. $username, 'dislikeNumber' => $newDislikeNumber, 'class' => 'success'));
            }
        }
        else{
            $newDislikeNumber = $dislikeNumber + 1;
            $comment->setCommentDislike($newDislikeNumber);
            $em->persist($comment);
            $em->flush();

            $setCookieArray = array("comment".$id => 1);
            $cookieJson = json_encode($setCookieArray);
            $cookie = new Cookie('intothemovieCookie', $cookieJson, strtotime("+1 year"));
            $response = new Response();
            $response->headers->setCookie($cookie);
            $response->send();
            return new JsonResponse(array('message' => 'Vous venez de dislike le commentaire de : '. $username, 'dislikeNumber' => $newDislikeNumber, 'class' => 'success'));
        }
    }
}
