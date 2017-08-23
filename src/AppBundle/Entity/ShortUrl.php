<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as ExtAssert;

/**
 * @ORM\Entity ShortUrl
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShortUrlRepository")
 * @ORM\Table(name="urls")
 * @ORM\HasLifecycleCallbacks()
 */
class ShortUrl
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Url(
     *     message = "The url {{ value }} is not a valid url.",
     * )
     * @ExtAssert\ContainsUrlVerify
     */
    private $original_url = null;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max = 100,
     *     maxMessage = "Your short url can't be longer than {{ limit }} characters",
     * )
     */
    private $short_url = null;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created_at;

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set original URL
     *
     * @param  string   $original_url
     * @return ShortUrl
     */
    public function setOriginalUrl($original_url)
    {
        $this->original_url = $original_url;

        return $this;
    }

    /**
     * Get original URL
     *
     * @return string
     */
    public function getOriginalUrl()
    {
        return $this->original_url;
    }

    /**
     * Set short URL
     *
     * @param  string   $short_url
     * @return ShortUrl
     */
    public function setShortUrl($short_url)
    {
        $this->short_url = $short_url;

        return $this;
    }

    /**
     * Get short URL
     *
     * @return string
     */
    public function getShortUrl()
    {
        return $this->short_url;
    }

    /**
     * Set createdAt
     *
     * @return ShortUrl
     */
    public function setCreatedAt()
    {
        $this->created_at = new \DateTime();

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
