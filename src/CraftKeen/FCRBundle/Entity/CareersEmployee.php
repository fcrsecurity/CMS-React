<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CareersEmployee
 *
 * @ORM\Table(name="careers_employee")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\CareersEmployeeRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class CareersEmployee extends BaseEntity
{
    use HrEntitiesPermissionsTrait;

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
     * @ORM\Column(name="name", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="text")
     * @Gedmo\Versioned
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="imageAlt", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $imageAlt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Gedmo\Versioned
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="questions", type="text")
     * @Gedmo\Versioned
     */
    private $questions;

    /**
     * @var CareersEmployee
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="CareersEmployee", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var CareersEmployee
     *
     * One CareersEmployee have One Copy of CareersEmployee.
     * @ORM\OneToOne(targetEntity="CareersEmployee", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * Get Name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set Id
     *
     * @param string $id
     *
     * @return CareersEmployee
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set name
     *
     * @param string $name
     *
     * @return CareersEmployee
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return CareersEmployee
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set img
     *
     * @param string $img
     *
     * @return CareersEmployee
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set imageAlt
     *
     * @param string $imageAlt
     *
     * @return CareersEmployee
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
     * Set description
     *
     * @param string $description
     *
     * @return CareersEmployee
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set questions
     *
     * @param string $questions
     *
     * @return CareersEmployee
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;

        return $this;
    }

    /**
     * Get questions
     *
     * @return string
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set langParent
     *
     * @param CareersEmployee $langParent
     *
     * @return CareersEmployee
     */
    public function setLangParent(CareersEmployee $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return CareersEmployee
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set copyOf
     *
     * @param CareersEmployee $copyOf
     *
     * @return CareersEmployee
     */
    public function setCopyOf(CareersEmployee $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return CareersEmployee
     */
    public function getCopyOf()
    {
        return $this->copyOf;
    }

    /**
     * Return showing fields
     *
     * @param $display
     *
     * @return array
     */
    public function getDisplayItems($display)
    {
        $baseList = [
            'id',
            'name',
            'position',
            'img',
            'imageAlt',
            'description',
            'questions',
            'status',
        ];
        switch ($display) {
            case 'index':
                return $baseList;
                break;
            case 'view':
                return $baseList;
                break;
            case 'translate':
                return $baseList;
                break;
        }

        return [];
    }

    /**
     * @return string
     */
    public function getEntityBaseRoute()
    {
        return 'admin_careers_employee_';
    }
}
