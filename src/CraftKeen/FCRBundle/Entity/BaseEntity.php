<?php

namespace CraftKeen\FCRBundle\Entity;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\CMS\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * BaseEntity
 *
 * @ORM\MappedSuperclass()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
abstract class BaseEntity
{
    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;

    /**
     * @var Language
     *
     * Many Properties have One Language.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\AdminBundle\Entity\Language")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id")
     */
    private $lang;

    /**
     * @var Site
     *
     * Many PressRelease have One Site.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\AdminBundle\Entity\Site")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    protected $site;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=true, options={"default" : "live"})
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="access", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $access;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=20, nullable=true)
     */
    protected $version;

    /**
     * @var string
     *
     * @ORM\Column(name="version_comment", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $versionComment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @var User
     *
     * Many Pages have One Creator.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $createdBy;

    /**
     * @var User
     *
     * Many Pages have One Editor.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $updatedBy;

    /** * @var string
     *
     * @ORM\Column(name="sort_order", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $sortOrder;

    /**
     * BaseEntity constructor.
     */
    public function __construct()
    {
        $this->access = $this->getDefaultAccess();
    }

    /**
     * Set site
     *
     * @param Site $site
     *
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
     * Set status
     *
     * @param string $status
     *
     * @return $this
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
     * Set access
     *
     * @param string $access
     *
     * @return $this
     */
    public function setAccess($access)
    {
        $this->access = $access;
        if (null == $access) {
            $this->access = $this->getDefaultAccess();
        }
        return $this;
    }

    /**
     * Get access
     *
     * @return string
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set version
     *
     * @param string $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set versionComment
     *
     * @param string $versionComment
     *
     * @return $this
     */
    public function setVersionComment($versionComment)
    {
        $this->versionComment = $versionComment;

        return $this;
    }

    /**
     * Get versionComment
     *
     * @return string
     */
    public function getVersionComment()
    {
        return $this->versionComment;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return $this
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

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set lang
     *
     * @param Language $lang
     *
     * @return $this
     */
    public function setLang(Language $lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return Language
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set Created By
     *
     * @param User|null $createdBy
     *
     * @return $this
     */
    public function setCreatedBy(User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set updated By
     *
     * @param User|null $updatedBy
     *
     * @return $this
     */
    public function setUpdatedBy(User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set sortOrder
     *
     * @param string $sortOrder
     *
     * @return $this
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return string
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Get DeletedAt
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set DeletedAt
     *
     * @param $deletedAt
     *
     * @return $this
     */
    public function setDeletedAt(\DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
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
            'status',
            'version',
            'created',
            'updated',
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
     * Return Sortable fields
     *
     * @param $display
     *
     * @return array
     */
    public function getSortableItems($display)
    {
        $baseList = [
            'title',
            'name',
            'id',
            'sortOrder',
            'type',
            'page',
            'property',
            'parent',
        ];

        switch ($display) {
            case 'index':
                return $baseList + ['firstName', 'lastName', 'email'];
                break;
            case 'translate':
                return $baseList;
                break;
        }

        return [];
    }

    /**
     * Return Image fields
     *
     * @param $display
     *
     * @return array
     */
    public function getImageItems($display)
    {
        $baseList = [
            'background',
            'img',
            'image',
            'icon',
        ];

        switch ($display) {
            case 'index':
                return $baseList;
                break;
            case 'show':
                return $baseList;
                break;
            case 'translate':
                return $baseList;
                break;
        }

        return [];
    }

    /**
     * Return DateTime fields
     *
     * @param $display
     *
     * @return array
     */
    public function getDateTimeItems($display)
    {
        $baseList = [
            'updated',
            'created',
            'date',
            'declaredDate',
            'maturityDate',
            'exDividendDate',
            'recordDate',
            'payableDate',
        ];

        switch ($display) {
            case 'index':
                return $baseList;
                break;
            case 'show':
                return $baseList + ['specialDividendInKind', 'maturityDate'];
                break;
            case 'translate':
                return $baseList + ['specialDividendInKind', 'maturityDate'];
                break;
        }

        return [];
    }

    /**
     * Returns Entity Base Route
     *
     * @return string
     */
    public function getEntityBaseRoute()
    {
        return '';
    }

    /**
     * Returns Serialized String of Entity User Access.
     *
     * @return string
     */
    public function getDefaultAccess()
    {
        return serialize([
            'CREATE' => [User::ROLE_CONTRIBUTOR],
            'READ' => null,
            'UPDATE' => [User::ROLE_CONTRIBUTOR, User::ROLE_EDITOR],
            'DELETE' => null,
            'APPROVE' => [User::ROLE_EDITOR],
        ]);
    }
}
