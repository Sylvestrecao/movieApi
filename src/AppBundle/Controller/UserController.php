<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
class UserController extends Controller
{
    /**
     * @Route("/user/profile", name="user_profile")
     * @Method("GET")
     */
    public function userIndexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user_id = $this->getUser()->getId();
        $favoriteMovies = $em->getRepository('AppBundle:Movie')->getUserFavoriteMovies($user_id);


        return $this->render('AppBundle:User:user-profile.html.twig', array(
           'favoriteMovies' => $favoriteMovies
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
            $data = $request->request->all();
            $user = $this->getUser();
            $movie = new Movie();

            $movie->setMovieDbId($data['Movie_Id']);
            $movie->setTitle($data['Movie_Title']);
            $movie->setPosterPath($data['Poster_Path']);
            $movie->addUser($user);
            $em->persist($movie);
            $em->flush();

        }

        return new JsonResponse($data);
    }
}
