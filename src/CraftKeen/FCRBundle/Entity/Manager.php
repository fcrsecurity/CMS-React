<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Manager
 *
 * @ORM\Table(name="leasing_manager")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\ManagerRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class Manager extends BaseEntity
{
    use LeasingCoordinatorEntitiesPermissionsTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="image_alt", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $imageAlt;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_extension", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $phoneExtension;

    /**
     * @var string
     *
     * @ORM\Column(name="tollfree", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $tollfree;

    /**
     * @var Manager
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="Manager", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var Manager
     *
     * One Manager have One Copy of Manager.
     * @ORM\OneToOne(targetEntity="Manager", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * Get Full Name of Manager
     *
     * @return string
     */
    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName.' ('.ucwords($this->type).') - '.$this->getLang();
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
     * Set Id
     *
     * @param integer $id
     *
     * @return Manager
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set type
     *
     * @param array $type
     *
     * @return Manager
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Manager
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Manager
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Manager
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
     * Set email
     *
     * @param string $email
     *
     * @return Manager
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
     * Set fax
     *
     * @param string $fax
     *
     * @return Manager
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Manager
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phoneExtension
     *
     * @param string $phoneExtension
     *
     * @return Manager
     */
    public function setPhoneExtension($phoneExtension)
    {
        $this->phoneExtension = $phoneExtension;

        return $this;
    }

    /**
     * Get phoneExtension
     *
     * @return string
     */
    public function getPhoneExtension()
    {
        return $this->phoneExtension;
    }

    /**
     * Set tollfree
     *
     * @param string $tollfree
     *
     * @return Manager
     */
    public function setTollfree($tollfree)
    {
        $this->tollfree = $tollfree;

        return $this;
    }

    /**
     * Get tollfree
     *
     * @return string
     */
    public function getTollfree()
    {
        return $this->tollfree;
    }

    /**
     * Set langParent
     *
     * @param Manager $langParent
     *
     * @return Manager
     */
    public function setLangParent(Manager $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return Manager
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Manager
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
     * @return Manager
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
     * Set copyOf
     *
     * @param Manager $copyOf
     *
     * @return Manager
     */
    public function setCopyOf(Manager $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return Manager
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
            'type',
            'image',
            'firstName',
            'lastName',
            'title',
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
    public function getEntityBaseRoute() {
        return 'admin_leasing_manager_';
    }
}
