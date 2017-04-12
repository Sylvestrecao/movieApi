<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;
/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class Comment
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
     * One Comment has Many Comments.
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent", cascade={"persist"})
     */
    private $children;

    /**
     * Many Comments have One Comment.
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="cascade")
     */
    private $parent;

    /**
     * @var int
     * @ORM\Column(name="commentLike", type="integer")
     */
    private $commentLike = 0;

    /**
     * @var int
     * @ORM\Column(name="commentDislike", type="integer")
     */
    private $commentDislike = 0;

    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @var int
     * @ORM\Column(name="movie_id", type="integer")
     */
    private $movie_id;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set movieId
     *
     * @param integer $movieId
     *
     * @return Comment
     */
    public function setMovieId($movieId)
    {
        $this->movie_id = $movieId;

        return $this;
    }

    /**
     * Get movieId
     *
     * @return integer
     */
    public function getMovieId()
    {
        return $this->movie_id;
    }

    /**
     * Add child
     *
     * @param \AppBundle\Entity\Comment $child
     *
     * @return Comment
     */
    public function addChild(\AppBundle\Entity\Comment $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \AppBundle\Entity\Comment $child
     */
    public function removeChild(\AppBundle\Entity\Comment $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Comment $parent
     *
     * @return Comment
     */
    public function setParent(\AppBundle\Entity\Comment $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Comment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Comment
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
     * Set commentLike
     *
     * @param integer $commentLike
     *
     * @return Comment
     */
    public function setCommentLike($commentLike)
    {
        $this->commentLike = $commentLike;

        return $this;
    }

    /**
     * Get commentLike
     *
     * @return integer
     */
    public function getCommentLike()
    {
        return $this->commentLike;
    }

    /**
     * Set commentDislike
     *
     * @param integer $commentDislike
     *
     * @return Comment
     */
    public function setCommentDislike($commentDislike)
    {
        $this->commentDislike = $commentDislike;

        return $this;
    }

    /**
     * Get commentDislike
     *
     * @return integer
     */
    public function getCommentDislike()
    {
        return $this->commentDislike;
    }
}
