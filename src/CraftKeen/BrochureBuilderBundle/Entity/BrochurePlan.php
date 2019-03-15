<?php

namespace CraftKeen\BrochureBuilderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BrochurePlan
 *
 * @ORM\Table(name="leasing_brochure_plan")
 * @ORM\Entity(repositoryClass="CraftKeen\BrochureBuilderBundle\Repository\BrochurePlanRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 *
 */
class BrochurePlan
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
     * @ORM\ManyToOne(targetEntity="Brochure", inversedBy="plans")
     * @ORM\JoinColumn(name="brochure_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $brochure;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=2048, nullable=true)
     * @Assert\NotBlank()
     */
    private $image;

    /**
     * @var array
     *
     * @ORM\Column(name="image_meta", type="array", nullable=true)
     * @Gedmo\Versioned
     */
    private $imageMeta;
    
    /**
     * @var string
     *
     * @ORM\Column(name="image_crop", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $imageCrop;

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
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return BrochurePlan
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Set imageMeta
     *
     * @param array $imageMeta
     *
     * @return BrochurePlan
     */
    public function setImageMeta($imageMeta)
    {
        $this->imageMeta = $imageMeta;

        return $this;
    }

    /**
     * Get imageMeta
     *
     * @return array
     */
    public function getImageMeta()
    {
        return $this->imageMeta;
    }

    /**
     * Set imageCrop
     *
     * @param string $imageCrop
     *
     * @return BrochurePlan
     */
    public function setImageCrop($imageCrop)
    {
        $this->imageCrop = $imageCrop;

        return $this;
    }

    /**
     * Get imageCrop
     *
     * @return string
     */
    public function getImageCrop()
    {
        return $this->imageCrop;
    }

    /**
     * Get brochure
     *
     * @return Brochure
     */
    public function getBrochure()
    {
        return $this->brochure;
    }

    /**
     * Set brochure
     *
     * @param Brochure $brochure
     *
     * @return BrochurePlan
     */
    public function setBrochure(Brochure $brochure)
    {
        $this->brochure = $brochure;

        return $this;
    }

    /**
     * @return array
     */
    public function toJson()
    {
        return [
            'image' => $this->getImage(),
            'imageMeta' => $this->getImageMeta()
        ];
    }
}
