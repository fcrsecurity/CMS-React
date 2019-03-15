<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PropertySubmission
 *
 * @ORM\Table(name="leasing_property_submission")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\PropertySubmissionRepository")
 */
class PropertySubmission
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
     * @var Property
     *
     * Many Images have One Property.
     * @ORM\ManyToOne(targetEntity="CraftKeen\FCRBundle\Entity\Property", inversedBy="submissions")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $property;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="inqury_type", type="string", length=255)
     */
    private $inquryType;

    /**
     * @var string
     *
     * @ORM\Column(name="square_footage", type="string", length=255)
     */
    private $squareFootage;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=255)
     */
    private $ipAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=true, options={"default" : "live"})
     */
    protected $status;

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
     * Set name
     *
     * @param string $name
     *
     * @return PropertySubmission
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
     * Set phone
     *
     * @param string $phone
     *
     * @return PropertySubmission
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
     * Set email
     *
     * @param string $email
     *
     * @return PropertySubmission
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
     * Set inquryType
     *
     * @param string $inquryType
     *
     * @return PropertySubmission
     */
    public function setInquryType($inquryType)
    {
        $this->inquryType = $inquryType;

        return $this;
    }

    /**
     * Get inquryType
     *
     * @return string
     */
    public function getInquryType()
    {
        return $this->inquryType;
    }

    /**
     * Set squareFootage
     *
     * @param string $squareFootage
     *
     * @return PropertySubmission
     */
    public function setSquareFootage($squareFootage)
    {
        $this->squareFootage = $squareFootage;

        return $this;
    }

    /**
     * Get squareFootage
     *
     * @return string
     */
    public function getSquareFootage()
    {
        return $this->squareFootage;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return PropertySubmission
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     *
     * @return PropertySubmission
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set created
     *
     * @param bool $created
     *
     * @return $this
     */
    public function setCreated($created = false)
    {
        if (!$created) {
            $this->created = new \DateTime();
        } else {
            $this->created = $created;
        }

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
     * Set property
     *
     * @param Property $property
     *
     * @return PropertySubmission
     */
    public function setProperty(Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
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
            'property',
            'name',
            'phone',
            'email',
            'inquryType',
            'squareFootage',
            'comment',
            'ipAddress',
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
     * Return Sortable fields
     * @param $display
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
                return $baseList + ['firstName','lastName','email'];
                break;
            case 'translate':
                return $baseList;
                break;
        }
        return [];
    }

    /**
     * Return Image fields
     * @param $display
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
     * @param $display
     * @return array
     */
    public function getDateTimeItems($display)
    {
        $baseList = [
            'updated',
            'created',
            'date',
            'declaredDate',
            'exDividendDate',
            'recordDate',
            'payableDate',
        ];

        switch ($display) {
            case 'index':
                return $baseList;
                break;
            case 'show':
                return $baseList + ['specialDividendInKind','maturityDate'];
                break;
            case 'translate':
                return $baseList;
                break;
        }
        return [];
    }
}
