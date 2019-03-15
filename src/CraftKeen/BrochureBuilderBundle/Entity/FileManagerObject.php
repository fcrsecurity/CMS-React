<?php
/**
 * Created by PhpStorm.
 * User: andreykopkin
 * Date: 21.11.17
 * Time: 9:37
 */

namespace CraftKeen\BrochureBuilderBundle\Entity;

use CraftKeen\CMS\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FileManagerObject
 *
 * Represents database mapping for brochure filemanager files and folders
 *
 * @ORM\Table(name="filemanager_object")
 * @ORM\Entity(repositoryClass="CraftKeen\BrochureBuilderBundle\Repository\FileManagerObjectRepository")
 * @Gedmo\Loggable
 */
class FileManagerObject
{
    const TYPE_FOLDER = 'folder';
    const TYPE_FILE = 'file';

    const STATUS_LIVE = 'live';
    const STATUS_DELETED = 'deleted';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var FileManagerObject
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="FileManagerObject", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $parent;

    /**
     * @var User
     *
     * Many Objects have One Creator.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $owner;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=1024)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="path", type="string", length=2048)
     * @Gedmo\Versioned
     */
    private $path;

    /**
     * @var string
     * @ORM\Column(name="meta", type="string", length=2048, nullable=true)
     * @Gedmo\Versioned
     */
    private $metaData;

    /**
     * @var string
     * @ORM\Column(name="aoda", type="string", length=512, nullable=true)
     * @Gedmo\Versioned
     */
    private $aodaData;

    /**
     * @var string
     * @ORM\Column(name="status", type="string", length=50, options={"default": "live"})
     * @Gedmo\Versioned
     */
    private $status = self::STATUS_LIVE;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=50, options={"default": "folder"})
     */
    private $type = self::TYPE_FOLDER;

    /**
     * @var string
     * @ORM\Column(name="mime", type="string", length=100)
     */
    private $mime;

    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FileManagerObject
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param FileManagerObject $parent
     * @return FileManagerObject
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     * @return FileManagerObject
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return FileManagerObject
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return FileManagerObject
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaData()
    {
        if (!empty($this->metaData)) {
            return $this->metaData;
        }
        if (!empty($this->parent)) {
            return $this->parent->getMetaData();
        }
        return null;
    }

    /**
     * @param string $metaData
     * @return FileManagerObject
     */
    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;
        return $this;
    }

    /**
     * @return string
     */
    public function getAodaData()
    {
        if (!empty($this->aodaData)) {
            return $this->aodaData;
        }
        if (!empty($this->parent)) {
            return $this->parent->getAodaData();
        }
        return null;
    }

    /**
     * @param string $aodaData
     * @return FileManagerObject
     */
    public function setAodaData($aodaData)
    {
        $this->aodaData = $aodaData;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return FileManagerObject
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return FileManagerObject
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @param string $mime
     * @return FileManagerObject
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return FileManagerObject
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * @param \DateTime $modifiedAt
     * @return FileManagerObject
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
        return $this;
    }



}
