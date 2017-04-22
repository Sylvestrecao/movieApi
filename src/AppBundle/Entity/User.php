<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="ProfileImage", cascade={"persist", "remove"})
     */
    private $profileImage;

    public function __construct()
    {
        parent::__construct();
    }

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
     * Set profileImage
     *
     * @param \AppBundle\Entity\ProfileImage $profileImage
     *
     * @return User
     */
    public function setProfileImage(\AppBundle\Entity\ProfileImage $profileImage = null)
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    /**
     * Get profileImage
     *
     * @return \AppBundle\Entity\ProfileImage
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }
}
