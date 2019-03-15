<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RetailArtGallery
 *
 * @ORM\Table(name="community_retail_art_gallery")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\PropertyGalleryRepository")
 */
class RetailArtGallery
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
     * @ORM\Column(name="image", type="text")
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="image_alt", type="string", length=150, nullable=true)
     */
    private $imageAlt;

    /**
     * @var int
     *
     * @ORM\Column(name="sort_order", type="integer", nullable=true)
     */
    private $sortOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=true, options={"default" : "live"})
     */
    protected $status;

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
     * Get vacantSqft
     *
     * @return string
     */
    public function __toString()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return RetailArtGallery
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set imageAlt
     *
     * @param string $imageAlt
     *
     * @return RetailArtGallery
     */
    public function setImageAlt($imageAlt)
    {
        $this->imageAlt = $imageAlt;

        return $this;
    }

    /**
     * Get imageAlt
     *
     * @return string
     */
    public function getImageAlt()
    {
        return $this->imageAlt;
    }

    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     *
     * @return RetailArtGallery
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return RetailArtGallery
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getEntityBaseRoute() {
        return 'admin_community_retail-art_gallery_';
    }
}
