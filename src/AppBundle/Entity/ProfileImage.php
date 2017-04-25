<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * ProfileImage
 *
 * @ORM\Table(name="profile_image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfileImageRepository")
 */
class ProfileImage
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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @Assert\Image()
     */
    private $file;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
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
     * Set url
     *
     * @param string $url
     *
     * @return ProfileImage
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return ProfileImage
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    public function upload()
    {
        if (null === $this->file) {
            return;
        }
        $fileName = uniqid().'.'.$this->file->guessExtension();
        $this->file->move(__DIR__.'/../../../web/uploads/img', $fileName);
        $this->url = $fileName;
        $this->alt = $this->file->getClientOriginalName();
    }

    public function removeProfileImage($profileImage)
    {
        $oldFile = $profileImage->getUploadRootDir().'/'.$profileImage->getUrl();
        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
    }
    public function getUploadRootDir()
    {
        return __DIR__.'/../../../web/uploads/img';
    }

    
}
