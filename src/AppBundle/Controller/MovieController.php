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
    public function indexAction(Request $request)
    {
        $nowPlayingMoviesSlider = Unirest\Request::get('https://api.themoviedb.org/3/movie/now_playing?api_key='.$this->getParameter('api_key').'&language=fr-FR&page=1&region=FR');
        $nowPlayingMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/now_playing?api_key='.$this->getParameter('api_key').'&language=fr-FR&page='.$request->query->get('page-a-laffiche').'&region=FR');
        $popularMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/popular?api_key='.$this->getParameter('api_key').'&language=fr-FR&page='.$request->query->get('page-populaire').'&region=FR');
        $upcomingMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/upcoming?api_key='.$this->getParameter('api_key').'&language=fr-FR&page='.$request->query->get('page-prochainement').'&region=FR');
        $topRatedMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/top_rated?api_key='.$this->getParameter('api_key').'&language=fr-FR&page='.$request->query->get('page-meilleurs-notes').'&region=FR');

        /* $currentPage = (int)$request->query->get('page-a-laffiche');
        if($currentPage < 1 || $currentPage > $nowPlayingMovies->body->total_pages){
            return $this->redirectToRoute('homepage', array('page-a-laffiche' => 1));
        } */
        
        return $this->render('AppBundle:Default:index.html.twig', array(
            'nowPlayingMoviesSlider' => $nowPlayingMoviesSlider,
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
    public function indexPageAction(Request $request, $page_number)
    {
        $nowPlayingMoviesSlider = Unirest\Request::get('https://api.themoviedb.org/3/movie/now_playing?api_key='.$this->getParameter('api_key').'&language=fr-FR&page=1&region=FR');
        $nowPlayingMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/now_playing?api_key='.$this->getParameter('api_key').'&language=fr-FR&page='.$request->query->get('page').'&region=FR');
        $popularMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/popular?api_key='.$this->getParameter('api_key').'&language=fr-FR&page='.$page_number.'&region=FR');
        $upcomingMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/upcoming?api_key='.$this->getParameter('api_key').'&language=fr-FR&page=1&region=FR');
        $topRatedMovies = Unirest\Request::get('https://api.themoviedb.org/3/movie/top_rated?api_key='.$this->getParameter('api_key').'&language=fr-FR&page=1&region=FR');

        return $this->render('AppBundle:Default:index.html.twig', array(
            'nowPlayingMoviesSlider' => $nowPlayingMoviesSlider,
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

    public function includePaginationAction($listMovies, $previousFormId, $nextFormId, $inputName, $action)
    {
        return $this->render('AppBundle:Default:include-pagination.html.twig', array(
            'listMovies' => $listMovies,
            'previousFormId' => $previousFormId,
            'nextFormId' => $nextFormId,
            'inputName' => $inputName,
            'action' => $action
        ));
    }

    /**
     * @Route("/movie/{movie_id}", name="movie_details", requirements={"movie_id": "\d+"})
     * @Method("GET")
     */
    public function showMovieDetailsAction($movie_id)
    {
        $em = $this->getDoctrine()->getManager();
        $movieDetails = Unirest\Request::get('https://api.themoviedb.org/3/movie/'.$movie_id.'?api_key='.$this->getParameter('api_key').'&language=fr-FR&append_to_response=videos,credits,recommendations');
        $movieComments = $em->getRepository('AppBundle:Comment')->getMovieComments($movie_id);
        
        return $this->render('AppBundle:Default:detail.html.twig', array(
            'movieDetails' => $movieDetails,
            'movieComments' => $movieComments
        ));
    }

    /**
     * @Route("/genre/{genre_id}/{genre_name}/movies", name="genre_movies", requirements={"genre_id": "\d+"})
     * @Method("GET")
     */
    public function genreMoviesAction(Request $request, $genre_id, $genre_name)
    {
        if($request->query->get($genre_name) == null){
            $genreMovies = Unirest\Request::get('https://api.themoviedb.org/3/genre/'.$genre_id.'/movies?api_key='.$this->getParameter('api_key').'&language=fr-FR&page=1&sort_by=created_at.desc');
        }
        else{
            $genreMovies = Unirest\Request::get('https://api.themoviedb.org/3/genre/'.$genre_id.'/movies?api_key='.$this->getParameter('api_key').'&language=fr-FR&page='.$request->query->get($genre_name).'&sort_by=created_at.desc');
        }

        return $this->render('AppBundle:Default:genre-movies.html.twig', array(
            'genreMovies' => $genreMovies,
            'genreId' => $genre_id,
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
     * @Route("/company/{company_id}/{company_name}/movies", name="company_movies", requirements={"company_id": "\d+", "company_name"=".+"})
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
