<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
/**
 * MovieUser
 * @ORM\Table(name="intomovie_user",uniqueConstraints={@UniqueConstraint(name="movie_user_unique", columns={"user_id", "movie_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieUserRepository")
 */
class MovieUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="favoriteMovie", type="boolean", nullable=true)
     */
    private $favoriteMovie;

    /**
     * @var bool
     *
     * @ORM\Column(name="movieToWatch", type="boolean", nullable=true)
     */
    private $movieToWatch;

    /**
     * @ORM\ManyToOne(targetEntity="Movie", inversedBy="movieUsers", cascade={"persist"})
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set favoriteMovie
     *
     * @param boolean $favoriteMovie
     *
     * @return MovieUser
     */
    public function setFavoriteMovie($favoriteMovie)
    {
        $this->favoriteMovie = $favoriteMovie;

        return $this;
    }

    /**
     * Get favoriteMovie
     *
     * @return bool
     */
    public function getFavoriteMovie()
    {
        return $this->favoriteMovie;
    }

    /**
     * Set movie
     *
     * @param \AppBundle\Entity\Movie $movie
     *
     * @return MovieUser
     */
    public function setMovie(\AppBundle\Entity\Movie $movie = null)
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * Get movie
     *
     * @return \AppBundle\Entity\Movie
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return MovieUser
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set movieToWatch
     *
     * @param boolean $movieToWatch
     *
     * @return MovieUser
     */
    public function setMovieToWatch($movieToWatch)
    {
        $this->movieToWatch = $movieToWatch;

        return $this;
    }

    /**
     * Get movieToWatch
     *
     * @return boolean
     */
    public function getMovieToWatch()
    {
        return $this->movieToWatch;
    }
}
