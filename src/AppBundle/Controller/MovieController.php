<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Unirest;
class MovieController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $popularMovies = Unirest\Request::get($this->getParameter('popularMovies'));
        $nowPlayingMovies = Unirest\Request::get($this->getParameter('nowPlayingMovies'));
        $upcomingMovies = Unirest\Request::get($this->getParameter('upcomingMovies'));
        $topRatedMovies = Unirest\Request::get($this->getParameter('topRatedMovies'));

        return $this->render('AppBundle:Default:index.html.twig', array(
            'popularMovies' => $popularMovies,
            'nowPlayingMovies' => $nowPlayingMovies,
            'upcomingMovies' => $upcomingMovies,
            'topRatedMovies' => $topRatedMovies
        ));
    }

    public function includeMovieResultsAction($listMovies)
    {
        return $this->render('AppBundle:Default:include-movie-results.html.twig', array(
            'listMovies' => $listMovies
        ));
    }

    /**
     * @Route("/movie/{movie_id}", name="movie_details", requirements={"movie_id": "\d+"})
     * @Method("GET")
     */
    public function showMovieDetailsAction($movie_id)
    {
        $movieDetails = Unirest\Request::get('https://api.themoviedb.org/3/movie/'.$movie_id.'?api_key=1ec8fb13de4288846a552aa419f958c2&language=fr-FR');
        $movieDetailsCredit = Unirest\Request::get('https://api.themoviedb.org/3/movie/'.$movie_id.'/credits?api_key=1ec8fb13de4288846a552aa419f958c2');

        return $this->render('AppBundle:Default:detail.html.twig', array(
            'movieDetails' => $movieDetails,
            'movieDetailsCredit' => $movieDetailsCredit
        ));
    }

    /**
     * @Route("/search/multi", name="movie_search")
     * @Method("GET")
     */
    public function movieSearchAction(Request $request)
    {
        $searchQuery = $request->query->get('searchQuery');

        $movieSearchResults = Unirest\Request::get('https://api.themoviedb.org/3/search/multi?api_key=1ec8fb13de4288846a552aa419f958c2&language=fr-FR&query='.$searchQuery.'&page=1&include_adult=false&region=FR');

        return $this->render('AppBundle:Default:search-result-page.html.twig', array(
            'movieSearchResults' => $movieSearchResults
        ));
    }
}
