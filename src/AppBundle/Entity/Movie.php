<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Movie
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieRepository")
 */
class Movie
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
     * @var int
     *
     * @ORM\Column(name="moviedb_id", type="integer")
     */
    private $movieDbId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="posterPath", type="string", length=255)
     */
    private $posterPath;

    /**
     * @ORM\OneToMany(targetEntity="MovieUser", mappedBy="movie", cascade={"persist"})
     */
    private $movieUsers;

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
     * Set title
     *
     * @param string $title
     *
     * @return Movie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set posterPath
     *
     * @param string $posterPath
     *
     * @return Movie
     */
    public function setPosterPath($posterPath)
    {
        $this->posterPath = $posterPath;

        return $this;
    }

    /**
     * Get posterPath
     *
     * @return string
     */
    public function getPosterPath()
    {
        return $this->posterPath;
    }
    
    /**
     * Set movieDbId
     *
     * @param integer $movieDbId
     *
     * @return Movie
     */
    public function setMovieDbId($movieDbId)
    {
        $this->movieDbId = $movieDbId;

        return $this;
    }

    /**
     * Get movieDbId
     *
     * @return integer
     */
    public function getMovieDbId()
    {
        return $this->movieDbId;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movieUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add movieUser
     *
     * @param \AppBundle\Entity\MovieUser $movieUser
     *
     * @return Movie
     */
    public function addMovieUser(\AppBundle\Entity\MovieUser $movieUser)
    {
        $this->movieUsers[] = $movieUser;
        $movieUser->setMovie($this);
        return $this;
    }

    /**
     * Remove movieUser
     *
     * @param \AppBundle\Entity\MovieUser $movieUser
     */
    public function removeMovieUser(\AppBundle\Entity\MovieUser $movieUser)
    {
        $this->movieUsers->removeElement($movieUser);
    }

    /**
     * Get movieUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovieUsers()
    {
        return $this->movieUsers;
    }
}
