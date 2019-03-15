<?php

namespace CraftKeen\FCRBundle\Entity;

use CraftKeen\CMS\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Property
 *
 * @ORM\Table(name="leasing_property")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\PropertyRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class Property extends BaseEntity
{
    use LeasingCoordinatorEntitiesPermissionsTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    private $id;

    /**
     * @var Site
     *
     * Many Pages have One Site.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\AdminBundle\Entity\Site", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    protected $site;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=150)
     * @Gedmo\Versioned
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="parent_name", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     */
    private $parentName;

    /**
     * @var string
     *
     * @ORM\Column(name="child_name", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     */
    private $childName;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $thumbnail;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail_alt", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $thumbnailAlt;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_vacant", type="boolean")
     * @Gedmo\Versioned
     */
    private $isVacant;

    /**
     * @var PropertyVacancy
     *
     * @ORM\OneToMany(targetEntity="PropertyVacancy", mappedBy="property", cascade={"persist"}, orphanRemoval=true)
     */
    private $vacancyList;

    /**
     * One Property has one Details List.
     *
     * @ORM\OneToOne(targetEntity="PropertyDetails", mappedBy="property", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $details;

    /**
     * One Property has one Details List.
     *
     * @ORM\OneToOne(targetEntity="PropertyFilter", mappedBy="property", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $filters;

    /**
     * One Customer has One Cart.
     * @ORM\OneToOne(targetEntity="PropertyDemographic", mappedBy="property", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $demographic;

    /**
     * @var PropertyGallery
     *
     * @ORM\OneToMany(targetEntity="PropertyGallery", mappedBy="property", cascade={"persist"}, orphanRemoval=true)
     */
    private $gallery;

    /**
     * @var PropertySubmission
     *
     * @ORM\OneToMany(targetEntity="PropertySubmission", mappedBy="property")
     */
    private $submissions;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Manager", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="leasing_property_managers",
     *      joinColumns={@ORM\JoinColumn(name="property_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="manager_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $managers;


    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Tenant")
     * @ORM\JoinTable(name="leasing_property_tenants",
     *      joinColumns={@ORM\JoinColumn(name="property_id", referencedColumnName="id", onDelete="cascade")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tenant_id", referencedColumnName="id")}
     *      )
     */
    private $tenants;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_green", type="boolean")
     * @Gedmo\Versioned
     */
    private $isGreen;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_boma", type="boolean")
     * @Gedmo\Versioned
     */
    private $isBoma;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_hidden", type="boolean")
     * @Gedmo\Versioned
     */
    private $isHidden;

    /**
     * @var Property
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="Property", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var Property
     *
     * One Property have One Copy of Property.
     * @ORM\OneToOne(targetEntity="Property", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * Property constructor
     */
    public function __construct()
    {
        $this->tenants = new ArrayCollection();
        $this->gallery = new ArrayCollection();
        $this->managers = new ArrayCollection();
        $this->vacancyList = new ArrayCollection();
        $this->submissions = new ArrayCollection();
        $this->access = $this->getDefaultAccess();
    }

    /**
     * Property clone
     */
    public function __clone()
    {
        if ($this->id) {
            $this->setId(null);
        }

        $this->setStatus('draft');

        if (null != $this->details) {
            $this->details = clone $this->details;
        }
        if (null != $this->demographic) {
            $this->demographic = clone $this->demographic;
        }
        if (null != $this->filters) {
            $this->filters = clone $this->filters;
        }

        $galleries = $this->getGallery();

        $this->gallery = new ArrayCollection();
        foreach ($galleries as $gallery) {
            $cloneGallery = clone $gallery;
            $this->gallery->add($cloneGallery);
        }
    }

    /**
     * Get childName
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->childName;
    }

    /**
     * Set Id
     *
     * @param integer $id
     *
     * @return Property
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set code
     *
     * @param string $code
     *
     * @return Property
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set parentName
     *
     * @param string $parentName
     *
     * @return Property
     */
    public function setParentName($parentName)
    {
        $this->parentName = $parentName;

        return $this;
    }

    /**
     * Get parentName
     *
     * @return string
     */
    public function getParentName()
    {
        return $this->parentName;
    }

    /**
     * Set childName
     *
     * @param string $childName
     *
     * @return Property
     */
    public function setChildName($childName)
    {
        $this->childName = $childName;

        return $this;
    }

    /**
     * Get childName
     *
     * @return string
     */
    public function getChildName()
    {
        return $this->childName;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return Property
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set isVacant
     *
     * @param boolean $isVacant
     *
     * @return Property
     */
    public function setIsVacant($isVacant)
    {
        $this->isVacant = $isVacant;

        return $this;
    }

    /**
     * Get isVacant
     *
     * @return boolean
     */
    public function getIsVacant()
    {
        return $this->isVacant;
    }

    /**
     * Set isGreen
     *
     * @param boolean $isGreen
     *
     * @return Property
     */
    public function setIsGreen($isGreen)
    {
        $this->isGreen = $isGreen;

        return $this;
    }

    /**
     * Get isGreen
     *
     * @return boolean
     */
    public function getIsGreen()
    {
        return $this->isGreen;
    }

    /**
     * Set isBoma
     *
     * @param boolean $isBoma
     *
     * @return Property
     */
    public function setIsBoma($isBoma)
    {
        $this->isBoma = $isBoma;

        return $this;
    }

    /**
     * Get isBoma
     *
     * @return boolean
     */
    public function getIsBoma()
    {
        return $this->isBoma;
    }

    /**
     * Set isHidden
     *
     * @param boolean $isHidden
     *
     * @return Property
     */
    public function setIsHidden($isHidden)
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    /**
     * Get isHidden
     *
     * @return boolean
     */
    public function getIsHidden()
    {
        return $this->isHidden;
    }

    /**
     * Set langParent
     *
     * @param Property $langParent
     *
     * @return Property
     */
    public function setLangParent(Property $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return Property
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Add vacancyList
     *
     * @param PropertyVacancy $vacancyList
     *
     * @return Property
     */
    public function addVacancyList(PropertyVacancy $vacancyList)
    {
        $this->vacancyList[] = $vacancyList;

        return $this;
    }

    /**
     * Remove vacancyList
     *
     * @param PropertyVacancy $vacancyList
     */
    public function removeVacancyList(PropertyVacancy $vacancyList)
    {
        $this->vacancyList->removeElement($vacancyList);
    }

    /**
     * Get vacancyList
     *
     * @return ArrayCollection
     */
    public function getVacancyList()
    {
        return $this->vacancyList;
    }

    /**
     * Add manager
     *
     * @param Manager $manager
     *
     * @return Property
     */
    public function addManager(Manager $manager)
    {
        $this->managers[] = $manager;

        return $this;
    }

    /**
     * Remove manager
     *
     * @param Manager $manager
     */
    public function removeManager(Manager $manager)
    {
        $this->managers->removeElement($manager);
    }

    /**
     * Get managers
     *
     * @return ArrayCollection
     */
    public function getManagers()
    {
        $iterator = $this->managers->getIterator();
        //dump($iterator);

        $iterator->uasort(function ($first, $second) {
            /** @var Manager $first */
            /** @var Manager $second */
            // TODO: Implement sortOrder
            // Temp. solution until ordering feature implemented in the admin. For now, Leasing Type goes first.
            return strlen($first->getType()) > strlen($second->getType()) ? 1 : -1;
        });

        return new ArrayCollection(iterator_to_array($iterator));
    }

    /**
     * Add tenant
     *
     * @param Tenant $tenant
     *
     * @return Property
     */
    public function addTenant(Tenant $tenant)
    {
        $this->tenants[] = $tenant;

        return $this;
    }

    /**
     * Remove tenant
     *
     * @param Tenant $tenant
     */
    public function removeTenant(Tenant $tenant)
    {
        $this->tenants->removeElement($tenant);
    }

    /**
     * Get tenants
     *
     * @return ArrayCollection
     */
    public function getTenants()
    {
        return $this->tenants;
    }

    /**
     * Add gallery
     *
     * @param PropertyGallery $gallery
     *
     * @return Property
     */
    public function addGallery(PropertyGallery $gallery)
    {
        $this->gallery[] = $gallery;

        return $this;
    }

    /**
     * Remove gallery
     *
     * @param PropertyGallery $gallery
     */
    public function removeGallery(PropertyGallery $gallery)
    {
        $this->gallery->removeElement($gallery);
    }

    /**
     * Get gallery
     *
     * @return ArrayCollection
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * Set details
     *
     * @param PropertyDetails $details
     *
     * @return Property
     */
    public function setDetails(PropertyDetails $details = null)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return PropertyDetails
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set filters
     *
     * @param PropertyFilter $filters
     *
     * @return Property
     */
    public function setFilters(PropertyFilter $filters = null)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Get filters
     *
     * @return PropertyFilter
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Get Demographic
     *
     * @return PropertyDemographic
     */
    public function getDemographic()
    {
        return $this->demographic;
    }

    /**
     * Get Submissions
     *
     * @return PropertySubmission
     */
    public function getSubmissions()
    {
        return $this->submissions;
    }

    /**
     * Set demographic
     *
     * @param PropertyDemographic $demographic
     *
     * @return Property
     */
    public function setDemographic(PropertyDemographic $demographic = null)
    {
        $this->demographic = $demographic;

        return $this;
    }

    /**
     * Add submission
     *
     * @param PropertySubmission $submission
     *
     * @return Property
     */
    public function addSubmission(PropertySubmission $submission)
    {
        $this->submissions[] = $submission;

        return $this;
    }

    /**
     * Remove submission
     *
     * @param PropertySubmission $submission
     */
    public function removeSubmission(PropertySubmission $submission)
    {
        $this->submissions->removeElement($submission);
    }


    /**
     * Set copyOf
     *
     * @param Property $copyOf
     *
     * @return Property
     */
    public function setCopyOf(Property $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return Property
     */
    public function getCopyOf()
    {
        return $this->copyOf;
    }

    /**
     * setSite
     *
     * @param Site $site
     * @return $this
     */
    public function setSite(Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @return string
     */
    public function getThumbnailAlt()
    {
        return $this->thumbnailAlt;
    }

    /**
     * @param string $thumbnailAlt
     * @return Property
     */
    public function setThumbnailAlt($thumbnailAlt)
    {
        $this->thumbnailAlt = $thumbnailAlt;

        return $this;
    }



    /**
     * Copy data from object
     *
     * @param Property $from
     *
     */
    public function copyDataFrom(Property $from)
    {
        $this->setCode($from->getCode());
        $this->setParentName($from->getParentName());
        $this->setChildName($from->getChildName());
        $this->setThumbnail($from->getThumbnail());
        $this->setThumbnailAlt($from->getThumbnailAlt());
        $this->setIsVacant($from->getIsVacant());
        $this->setIsGreen($from->getIsGreen());
        $this->setIsBoma($from->getIsBoma());
        $this->setIsHidden($from->getIsHidden());
        $this->setLangParent($from->getLangParent());
        $this->setSortOrder($from->getSortOrder());
        $this->setSite($from->getSite());
        $this->setVersionComment($from->getVersionComment());

        // Delete all VacancyList items from copy
        foreach ($this->getVacancyList() as $vacancyListItem) {
            $this->removeVacancyList($vacancyListItem);
        }
        // Add VacancyList items from object
        foreach ($from->getVacancyList() as $vacancyListItem) {
            /** @var PropertyVacancy $cloneVacancyListItem */
            $cloneVacancyListItem = clone $vacancyListItem;
            $cloneVacancyListItem->setProperty($this);
            $this->vacancyList->add($cloneVacancyListItem);
        }

        // Delete all Galleries items from copy
        foreach ($this->getGallery() as $gallery) {
            $this->removeGallery($gallery);
        }
        // Add Galleries items from object
        foreach ($from->getGallery() as $gallery) {
            /** @var PropertyGallery $cloneGallery */
            $cloneGallery = clone $gallery;
            $cloneGallery->setProperty($this);
            $this->gallery->add($cloneGallery);
        }

        // Delete all Tenants items from copy
        foreach ($this->getTenants() as $tenant) {
            $this->removeTenant($tenant);
        }
        // Add Tenants items from object
        foreach ($from->getTenants() as $tenant) {
            $this->addTenant($tenant);
        }

        // Delete all Managers items from copy
        foreach ($this->getManagers() as $manager) {
            $this->removeManager($manager);
        }
        // Add Managers items from object
        foreach ($from->getManagers() as $manager) {
            $this->addManager($manager);
        }
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
            'parentName',
            'childName',
            'code',
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
        return 'craftkeen_fcr_admin_leasing_property_';
    }

    /**
     * Apply Custom Permissions Access for this Entity.
     *
     * @return string
     */
    public function getDefaultAccess()
    {
        return serialize([
            'CREATE' => null,
            'READ' => null,
            'UPDATE' => [User::ROLE_LEASING],
            'DELETE' => null,
            'APPROVE' => [User::ROLE_LEASING],
        ]);
    }
}
