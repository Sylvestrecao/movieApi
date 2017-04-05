<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Unirest;
use DateTime;
class MovieController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction()
    {
        $nowPlayingMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/now_playing?api_key='.$this->getParameter('api_key').'&language=fr-FR&page=1&region=FR');
        $popularMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/popular?api_key='.$this->getParameter('api_key').'&language=fr-FR&page=1&region=FR');
        $upcomingMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/upcoming?api_key='.$this->getParameter('api_key').'&language=fr-FR&page=1&region=FR');
        $topRatedMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/top_rated?api_key='.$this->getParameter('api_key').'&language=fr-FR&page=1&region=FR');

        return $this->render('AppBundle:Default:index.html.twig', array(
            'nowPlayingMovies' => $nowPlayingMovies,
            'popularMovies' => $popularMovies,
            'upcomingMovies' => $upcomingMovies,
            'topRatedMovies' => $topRatedMovies
        ));
    }

    /**
     * @Route("/page/{page_number}", name="homepage_bis", requirements={"page_number": "\d+"})
     * @Method("GET")
     */
    public function indexPageAction($page_number)
    {
        $nowPlayingMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/now_playing?api_key='.$this->getParameter('api_key').'&language=fr-FR&page='.$page_number.'&region=FR');
        $popularMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/popular?api_key='.$this->getParameter('api_key').'&language=fr-FR&page=1&region=FR');
        $upcomingMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/upcoming?api_key='.$this->getParameter('api_key').'&language=fr-FR&page=1&region=FR');
        $topRatedMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/top_rated?api_key='.$this->getParameter('api_key').'&language=fr-FR&page=1&region=FR');

        return $this->render('AppBundle:Default:index.html.twig', array(
            'nowPlayingMovies' => $nowPlayingMovies,
            'popularMovies' => $popularMovies,
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
        $movieDetails = Unirest\Request::get('https://api.themoviedb.org/3/movie/'.$movie_id.'?api_key='.$this->getParameter('api_key').'&language=fr-FR&append_to_response=videos,credits,recommendations');

        return $this->render('AppBundle:Default:detail.html.twig', array(
            'movieDetails' => $movieDetails
        ));
    }

    /**
     * @Route("/genre/{genre_id}/{genre_name}/movies", name="genre_movies", requirements={"genre_id": "\d+"})
     * @Method("GET")
     */
    public function genreMoviesAction($genre_id, $genre_name)
    {
        $genreMovies = Unirest\Request::get('https://api.themoviedb.org/3/genre/'.$genre_id.'/movies?api_key='.$this->getParameter('api_key').'&language=fr-FR&include_adult=false&sort_by=created_at.desc');

        return $this->render('AppBundle:Default:genre-movies.html.twig', array(
            'genreMovies' => $genreMovies,
            'genreName'   => $genre_name
        ));
    }

    /**
     * @Route("/person/{person_id}", name="people_details", requirements={"person_id": "\d+"})
     * @Method("GET")
     */
    public function showPeopleDetailsAction($person_id)
    {
        $peopleDetails = Unirest\Request::get('https://api.themoviedb.org/3/person/'.$person_id.'?api_key='.$this->getParameter('api_key').'&language=en-US&append_to_response=movie_credits');

        $birthday = new DateTime($peopleDetails->body->birthday);
        $today = new DateTime("today");
        $actorAge = $today->diff($birthday)->y;

        return $this->render('AppBundle:Default:people-details.html.twig', array(
            'peopleDetails' => $peopleDetails,
            'actorAge' => $actorAge
        ));
    }

    /**
     * @Route("/search/multi", name="movie_search")
     * @Method("GET")
     */
    public function movieSearchAction(Request $request)
    {
        $searchQuery = $request->query->get('searchQuery');

        $movieSearchResults = Unirest\Request::get('https://api.themoviedb.org/3/search/multi?api_key='.$this->getParameter('api_key').'&language=fr-FR&query='.$searchQuery.'&page=1&include_adult=false&region=FR');

        return $this->render('AppBundle:Default:search-result-page.html.twig', array(
            'movieSearchResults' => $movieSearchResults
        ));
    }

    /**
     * @Route("/company/{company_id}/{company_name}/movies", name="company_movies", requirements={"company_id": "\d+"})
     * @Method("GET")
     */
    public function companyMoviesAction($company_id, $company_name)
    {
        $companyMovies = Unirest\Request::get('https://api.themoviedb.org/3/company/'.$company_id.'/movies?api_key='.$this->getParameter('api_key').'&language=fr-FR');

        return $this->render('AppBundle:Default:company-movies.html.twig', array(
            'companyMovies' => $companyMovies,
            'companyName'  => $company_name
        ));
    }

}
