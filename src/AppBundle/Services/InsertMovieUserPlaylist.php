<?php
namespace AppBundle\Services;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Movie;
use AppBundle\Entity\MovieUser;
class InsertMovieUserPlaylist
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function insertFavoriteMovie($user, $data)
    {
        $movieInDatabase = $this->em->getRepository('AppBundle:Movie')->findMovieInDatabase($data['Movie_Id']);
        if(isset($movieInDatabase)){
            // Get movieUser object
            $movieUserInDatabase = $this->em->getRepository('AppBundle:MovieUser')->findMovieUserInDatabase($user->getId(), $movieInDatabase->getId());
            if(isset($movieUserInDatabase))
                $favoriteMovie = $movieUserInDatabase->getFavoriteMovie();
        }
        // If movie is in database and movieUser does not exist, only add new movieUser
        if(isset($movieInDatabase) && empty($movieUserInDatabase)){
            $movieUser = new MovieUser();
            $movieUser->setFavoriteMovie(true);
            $movieUser->setUser($user);
            $movieUser->setMovie($movieInDatabase);

            $this->em->persist($movieUser);
            $state = "success";
        }
        // If movie is in database and favorite exist
        elseif(isset($movieInDatabase) && $favoriteMovie != null){
            $state = "error";
        }
        elseif(isset($movieInDatabase) && $favoriteMovie == null){
            $movieUserInDatabase->setFavoriteMovie(true);
            $state = "success";
        }
        // If movie does not exist
        else{
            $movie = new Movie();
            $movie->setMovieDbId($data['Movie_Id']);
            $movie->setTitle($data['Movie_Title']);
            $movie->setPosterPath($data['Poster_Path']);

            $movieUser = new MovieUser();
            $movieUser->setFavoriteMovie(true);
            $movieUser->setUser($user);
            $movieUser->setMovie($movie);

            $this->em->persist($movieUser);
            $state = "success";
        }
        $this->em->flush();

        return $state;
    }

    public function insertMovieToWatch($user, $data)
    {
        $movieInDatabase = $this->em->getRepository('AppBundle:Movie')->findMovieInDatabase($data['Movie_Id']);
        if(isset($movieInDatabase)){
            $movieUserInDatabase = $this->em->getRepository('AppBundle:MovieUser')->findMovieUserInDatabase($user->getId(), $movieInDatabase->getId());
            if(isset($movieUserInDatabase))
                $movieToWatch = $movieUserInDatabase->getMovieToWatch();
        }
        if(isset($movieInDatabase) && empty($movieUserInDatabase)){
            $movieUser = new MovieUser();
            $movieUser->setMovieToWatch(true);
            $movieUser->setUser($user);
            $movieUser->setMovie($movieInDatabase);

            $this->em->persist($movieUser);
            $state = "success";
        }
        elseif(isset($movieInDatabase) && $movieToWatch != null){
            $state = "error";
        }
        elseif(isset($movieInDatabase) && $movieToWatch == null){
            $movieUserInDatabase->setMovieToWatch(true);
            $state = "success";
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

            $this->em->persist($movieUser);
            $state = "success";
        }
        $this->em->flush();
        return $state;
    }


}