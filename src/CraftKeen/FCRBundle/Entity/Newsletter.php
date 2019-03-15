<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Newsletter
 *
 * @ORM\Table(name="newsletter")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\NewsletterRepository")
 */
class Newsletter
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
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="leasing", type="boolean")
     */
    private $leasing;

    /**
     * @var bool
     *
     * @ORM\Column(name="news", type="boolean")
     */
    private $news;

    /**
     * @var bool
     *
     * @ORM\Column(name="careers", type="boolean")
     */
    private $careers;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


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
     * Set email
     *
     * @param string $email
     *
     * @return Newsletter
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set leasing
     *
     * @param boolean $leasing
     *
     * @return Newsletter
     */
    public function setLeasing($leasing)
    {
        $this->leasing = $leasing;

        return $this;
    }

    /**
     * Get leasing
     *
     * @return bool
     */
    public function getLeasing()
    {
        return $this->leasing;
    }

    /**
     * Set news
     *
     * @param boolean $news
     *
     * @return Newsletter
     */
    public function setNews($news)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * Get news
     *
     * @return bool
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set careers
     *
     * @param boolean $careers
     *
     * @return Newsletter
     */
    public function setCareers($careers)
    {
        $this->careers = $careers;

        return $this;
    }

    /**
     * Get careers
     *
     * @return bool
     */
    public function getCareers()
    {
        return $this->careers;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Newsletter
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Newsletter
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}

