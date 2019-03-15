<?php

namespace CraftKeen\BrochureBuilderBundle\Entity;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\FCRBundle\Entity\BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use CraftKeen\FCRBundle\Entity\Property;
use CraftKeen\FCRBundle\Entity\Office;
use CraftKeen\FCRBundle\Entity\LeasingCoordinatorEntitiesPermissionsTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Brochure
 *
 * @ORM\Table(name="leasing_brochure")
 * @ORM\Entity(repositoryClass="CraftKeen\BrochureBuilderBundle\Repository\BrochureRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class Brochure extends BaseEntity
{
    use LeasingCoordinatorEntitiesPermissionsTrait;

    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_APPROVAL = 'pending_approval';
    const STATUS_LIVE = 'live';
    const STATUS_DELETED = 'deleted';

    const TRANSITION_REVERT = 'revert';
    const TRANSITION_RETRACT = 'retract';
    const TRANSITION_DELETE = 'delete';
    const TRANSITION_PUBLISH = 'publish';
    const TRANSITION_ARCHIVE = 'archive';
    const TRANSITION_REVIEW = 'to_review';
    const TRANSITION_REJECT = 'reject';

    const TEXT_FIELD_MAX_PREVIEW_LENGTH = 80;

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
     * @var Brochure
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="Brochure", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var Property
     *
     * Many Brochures have One Property.
     * @ORM\ManyToOne(targetEntity="CraftKeen\FCRBundle\Entity\Property")
     * @ORM\JoinColumn(nullable=false,name="property_id", referencedColumnName="id", onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    private $property;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="postal", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $postal;

    /**
     * @var string
     *
     * @ORM\Column(name="address1", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $address2;

    /**
     * @ORM\OneToOne(targetEntity="BrochureImage", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="hero_image_id", referencedColumnName="id")
     */
    private $heroImage;

    /**
     * @ORM\OneToOne(targetEntity="BrochureImage", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="contact_image_id", referencedColumnName="id")
     */
    private $contactImage;

    /**
     * @ORM\OneToOne(targetEntity="BrochureImage", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="contact_lifestyle_image_id", referencedColumnName="id")
     */
    private $contactLifestyleImage;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\Column(name="tenants", type="array", nullable=true)
     */
    private $tenants;

    /**
     * @var array
     *
     * @ORM\Column(name="contacts", type="array", nullable=true)
     */
    private $contacts;
    
    /**
     * @ORM\OneToOne(targetEntity="BrochureDemographic", mappedBy="brochure", cascade={"persist"})
     */
    private $demographic;

    /**
     * @ORM\OneToMany(targetEntity="BrochurePlan", mappedBy="brochure", cascade={"persist"}, orphanRemoval=true)
     */
    private $plans;

    /**
     * @var string
     *
     * @ORM\Column(name="location_latitude", type="float", nullable=true)
     * @Gedmo\Versioned
     * @Assert\Type("float")
     */
    private $locationLatitude;

    /**
     * @var string
     *
     * @ORM\Column(name="location_longitude", type="float", nullable=true)
     * @Gedmo\Versioned
     * @Assert\Type("float")
     */
    private $locationLongitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="location_zoom", type="integer", nullable=true)
     * @Gedmo\Versioned
     * @Assert\Type("integer")
     */
    private $locationZoom;

    /**
     * @var string
     *
     * @ORM\Column(name="intersection", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $intersection;

    /**
     * @var string
     *
     * @ORM\Column(name="office_header", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $officeHeader;

    /**
     * @var string
     *
     * @ORM\Column(name="office_line1", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $officeLine1;

    /**
     * @var string
     *
     * @ORM\Column(name="office_line2", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $officeLine2;

    /**
     * @var string
     *
     * @ORM\Column(name="pdf", type="string", length=2048, nullable=true)
     * @Gedmo\Versioned
     */
    private $pdf;

    /**
     * @var Brochure
     *
     * One Brochure have One Copy of Brochure.
     * @ORM\OneToOne(targetEntity="Brochure", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * @var string
     *
     * @ORM\Column(name="reject_comment", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $rejectComment;

    /**
     * @var string
     *
     * @ORM\Column(name="revert_comment", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $revertComment;

    /**
     * @var string
     *
     * @ORM\Column(name="hide_tag_line", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $hideTagLine = false;


    /**
     * Property constructor
     */
    public function __construct()
    {
        $this->plans = new ArrayCollection();
    }

    /**
     * Set copyOf
     *
     * @param Brochure $copyOf
     *
     * @return Brochure
     */
    public function setCopyOf(Brochure $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return Brochure
     */
    public function getCopyOf()
    {
        return $this->copyOf;
    }

    /**
     * Populate data from ajax request
     *
     * @param array $content
     *
     * @return Brochure
     */
    public function populateByAjaxContent(array $content)
    {
        if (isset($content['cover'])) {
            if (isset($content['cover']['description'])) {
                $this->setDescription(trim($content['cover']['description']));
            }
            if (isset($content['cover']['hideTagLine'])) {
                $this->setHideTagLine(!!$content['cover']['hideTagLine']);
            }
            if (isset($content['cover']['tenants']) && is_array($content['cover']['tenants'])) {
                $this->setTenants($content['cover']['tenants']);
            }
            if (isset($content['cover']['image']['crop'], $content['cover']['image']['meta'], $content['cover']['image']['src'])) {
                $heroImage = $this->getHeroImage() ?: new BrochureImage();
                $heroImage->setImage($content['cover']['image']['src']);
                $heroImage->setImageCrop($content['cover']['image']['crop']);
                $heroImage->setImageMeta($content['cover']['image']['meta']);
                $this->setHeroImage($heroImage);
            }
        }

        if (isset($content['demographic'])) {
            if (isset($content['demographic']['image']['crop'], $content['demographic']['image']['meta'], $content['demographic']['image']['src'])) {
                $demographicImage = $this->getDemographic()->getImage() ?: new BrochureImage();
                $demographicImage->setImage($content['demographic']['image']['src']);
                $demographicImage->setImageCrop($content['demographic']['image']['crop']);
                $demographicImage->setImageMeta($content['demographic']['image']['meta']);
                $this->getDemographic()->setImage($demographicImage);
            }
        }

        if (isset($content['contact'])) {
            if (isset($content['contact']['image']['crop'], $content['contact']['image']['meta'], $content['contact']['image']['src'])) {
                $contactImage = $this->getContactImage() ?: new BrochureImage();
                $contactImage->setImage($content['contact']['image']['src']);
                $contactImage->setImageCrop($content['contact']['image']['crop']);
                $contactImage->setImageMeta($content['contact']['image']['meta']);
                $this->setContactImage($contactImage);
            }
            if (isset($content['contact']['lifestyleImage']['crop'], $content['contact']['lifestyleImage']['meta'], $content['contact']['lifestyleImage']['src'])) {
                $contactLifestyleImage = $this->getContactLifestyleImage() ?: new BrochureImage();
                $contactLifestyleImage->setImage($content['contact']['lifestyleImage']['src']);
                $contactLifestyleImage->setImageCrop($content['contact']['lifestyleImage']['crop']);
                $contactLifestyleImage->setImageMeta($content['contact']['lifestyleImage']['meta']);
                $this->setContactLifestyleImage($contactLifestyleImage);
            }
            if (isset($content['contact']['office']) && is_array($content['contact']['office'])) {
                //$this->setOfficeHeader($content['contact']['office']['header']);
                $this->setOfficeLine1($content['contact']['office']['line1']);
                $this->setOfficeLine2($content['contact']['office']['line2']);
            }
            if (isset($content['contact']['location']['latitude'], $content['contact']['location']['longitude'])) {
                $this->setLocationLatitude(floatval($content['contact']['location']['latitude']));
                $this->setLocationLongitude(floatval($content['contact']['location']['longitude']));
            }
            if (isset($content['contact']['zoom'])) {
                $this->setLocationZoom(intval($content['contact']['zoom']));
            }
        }

        $this->getPlans()->clear();
        if (isset($content['plans']) & is_array($content['plans'])) {
            foreach($content['plans'] as $item) {
                if ($item && isset($item['crop'], $item['meta'], $item['src'])) {
                    $plan = new BrochurePlan();
                    $plan->setImage($item['src']);
                    $plan->setImageMeta($item['meta']);
                    $plan->setImageCrop($item['crop']);
                    $this->addPlan($plan);
                }
            }
        }

        return $this;
    }

    /**
     * @param string $html
     *
     * @return string
     */
    private function html2text($html)
    {
        return html_entity_decode(trim(strip_tags(
            preg_replace('/\<br(\s*)?\/?\>/i', "\n", $html)
        )), ENT_QUOTES | ENT_HTML401, 'UTF-8');
    }

    /**
     * Populate data from property object
     *
     * @param Property $property
     *
     * @param Brochure|null $parent
     * @return Brochure
     */
    public function populateByProperty(Property $property, Brochure $parent = null)
    {
        $this->setLang($property->getLang());
        $this->setProperty($property);
        $this->setLangParent($parent);
        $this->setVersion(null !== $parent ? intval($parent->getVersion()) + 1 : 1);
        $this->setVersionComment('Generated from property');
        $this->setCreated(new \DateTime());
        $this->setStatus(Brochure::STATUS_DRAFT);

        $this->setName($property->getParentName());

        $details = $property->getDetails();
        if (is_null($details) && !is_null($property->getLangParent())) {
            $details = $property->getLangParent()->getDetails();
        }

        if(!is_null($details)) {
            $this->setCity($details->getGeoCity());
            $this->setProvince($details->getGeoProvince());
            $this->setPostal($details->getGeoPostal());

            $this->setAddress1($details->getGeoAddress1());
            $this->setAddress2($details->getGeoAddress2());

            $this->setDescription($this->html2text($details->getDescription()));

            $this->setLocationLatitude($details->getGeoLat());
            $this->setLocationLongitude($details->getGeoLng());
            $this->setLocationZoom(17);
            $this->setIntersection($details->getGeoIntersetion());

            $heroImage = $this->getHeroImage() ?: new BrochureImage();
            $heroImage->setImage($details->getHeroImage());
            $this->setHeroImage($heroImage);
        }

        // demographic
        $demographic = new BrochureDemographic();
        $demographic->setBrochure($this);

        if ($property->getDemographic()) {
            $demographic->copyDataFrom($property->getDemographic());
        } 
        
        if($property->getLangParent() && $property->getLangParent()->getDemographic()) {
            $demographic->mergeDataFrom($property->getLangParent()->getDemographic());
        }

        $this->setDemographic($demographic);

        // tenants
        $this->setTenants(array_map(function($item){
                return $item->getImage();
            }, $property->getTenants()->toArray()
        ));

        // leasing managers
        $contacts = [];
        foreach ($property->getManagers() as $manager) {
            if ($manager->getType() === 'leasing' && $manager->getStatus() === 'live') {
                $contacts[] = [
                    'firstName' => $manager->getFirstName(),
                    'lastName' => $manager->getLastName(),
                    'title' => $manager->getTitle(),
                    'email' => $manager->getEmail(),
                    'fax' => $manager->getFax(),
                    'phone' => $manager->getPhone(),
                    'phoneExtension' => $manager->getPhoneExtension(),
                    'sortOrder' => $manager->getSortOrder() ?: 0
                ];
            }
        }

        usort($contacts, function($a, $b) {
            if ($a['sortOrder'] === $b['sortOrder']) {
                return 0;
            }
            return $a['sortOrder'] > $b['sortOrder'] ? -1 : 1;
        });

        $this->setContacts($contacts);

        return $this;
    }

    /**
     * Populate data from property object
     *
     * @param Office $office
     *
     * @return Brochure
     */
    public function populateByOffice(Office $office)
    {
        $fcr = 'First Capital Realty Inc.';
        $this->setOfficeHeader($fcr);
        $this->setOfficeLine1($office->getAddress());
        $this->setOfficeLine2($office->getCity().', '.$office->getProvince().', '.$office->getPostal());
        return $this;
    }

    /**
     * @return array
     */
    public function toJson()
    {
        $heroImage = $this->getHeroImage();
        $contactImage = $this->getContactImage();
        $contactLifestyleImage = $this->getContactLifestyleImage();

        return [
            'propertyId' => $this->getProperty()->getId(),
            'name' => $this->getName() ?: '',
            'description' => $this->getDescription() ?: '',
            'hideTagLine' => $this->getHideTagLine(),
            'lang' => $this->getLang()->getCode(),

            'city' => $this->getCity() ?: '',
            'province' => $this->getProvince() ?: '',
            'postal' => $this->getPostal() ?: '',
            'intersection' => $this->getIntersection() ?: '',
            'address1' => $this->getAddress1() ?: '',
            'address2' => $this->getAddress2() ?: '',

            'heroImage' => $heroImage ? $heroImage->toJson() : null,
            'contactImage' => $contactImage ? $contactImage->toJson() : null,
            'contactLifestyleImage' => $contactLifestyleImage ? $contactLifestyleImage->toJson() : null,

            'contacts' => $this->getContacts(),
            'tenants' => $this->getTenants(),

            //'officeHeader' => $this->getOfficeHeader() ?: '',
            'officeHeader' => 'First Capital Realty Inc.',
            'officeLine1' => $this->getOfficeLine1() ?: '',
            'officeLine2' => $this->getOfficeLine2() ?: '',

            'latitude' => $this->getLocationLatitude() ?: 0,
            'longitude' => $this->getLocationLongitude() ?: 0,
            'zoom' => $this->getLocationZoom() ?: 17,

            'demographic' => $this->getDemographic()->toJson(),

            'plans' => \array_map(function($item) {
                return $item->toJson();
            }, $this->getPlans()->toArray())
        ];
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
     * @return Brochure
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
     * Set langId
     *
     * @param integer $langId
     *
     * @return Brochure
     */
    public function setLangId($langId)
    {
        $this->langId = $langId;

        return $this;
    }

    /**
     * Get langId
     *
     * @return integer
     */
    public function getLangId()
    {
        return $this->langId;
    }


    /**
     * Set city
     *
     * @param string $city
     *
     * @return Brochure
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set province
     *
     * @param string $province
     *
     * @return Brochure
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province
     *
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Brochure
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
     * Set tenants
     *
     * @param array $tenants
     *
     * @return Brochure
     */
    public function setTenants($tenants)
    {
        $this->tenants = $tenants;

        return $this;
    }

    /**
     * Get tenants
     *
     * @return array
     */
    public function getTenants()
    {
        return $this->tenants;
    }

    /**
     * Set contacts
     *
     * @param array $contacts
     *
     * @return Brochure
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Get contacts
     *
     * @return array
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set locationLatitude
     *
     * @param float $locationLatitude
     *
     * @return Brochure
     */
    public function setLocationLatitude($locationLatitude)
    {
        $this->locationLatitude = $locationLatitude;

        return $this;
    }

    /**
     * Get locationLatitude
     *
     * @return float
     */
    public function getLocationLatitude()
    {
        return $this->locationLatitude;
    }

    /**
     * Set locationLongitude
     *
     * @param float $locationLongitude
     *
     * @return Brochure
     */
    public function setLocationLongitude($locationLongitude)
    {
        $this->locationLongitude = $locationLongitude;

        return $this;
    }

    /**
     * Get locationLongitude
     *
     * @return float
     */
    public function getLocationLongitude()
    {
        return $this->locationLongitude;
    }

    /**
     * Set locationZoom
     *
     * @param integer $locationZoom
     *
     * @return Brochure
     */
    public function setLocationZoom($locationZoom)
    {
        $this->locationZoom = $locationZoom;

        return $this;
    }

    /**
     * Get locationZoom
     *
     * @return integer
     */
    public function getLocationZoom()
    {
        return $this->locationZoom ?: 17;
    }

    /**
     * Set demographic
     *
     * @param \CraftKeen\BrochureBuilderBundle\Entity\BrochureDemographic $demographic
     *
     * @return Brochure
     */
    public function setDemographic(\CraftKeen\BrochureBuilderBundle\Entity\BrochureDemographic $demographic = null)
    {
        $this->demographic = $demographic;

        return $this;
    }

    /**
     * Get demographic
     *
     * @return \CraftKeen\BrochureBuilderBundle\Entity\BrochureDemographic
     */
    public function getDemographic()
    {
        return $this->demographic;
    }

    /**
     * Add plan
     *
     * @param \CraftKeen\BrochureBuilderBundle\Entity\BrochurePlan $plan
     *
     * @return Brochure
     */
    public function addPlan(\CraftKeen\BrochureBuilderBundle\Entity\BrochurePlan $plan)
    {
        $plan->setBrochure($this);
        $this->plans[] = $plan;

        return $this;
    }

    /**
     * Remove plan
     *
     * @param \CraftKeen\BrochureBuilderBundle\Entity\BrochurePlan $plan
     */
    public function removePlan(\CraftKeen\BrochureBuilderBundle\Entity\BrochurePlan $plan)
    {
        $this->plans->removeElement($plan);
    }

    /**
     * Get plans
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlans()
    {
        return $this->plans;
    }

    /**
     * Get pdf
     *
     * @return string
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * Set pdf
     *
     * @param string $pdf
     *
     * @return Brochure
     */
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * Set intersection
     *
     * @param string $intersection
     *
     * @return Brochure
     */
    public function setIntersection($intersection)
    {
        $this->intersection = $intersection;

        return $this;
    }

    /**
     * Get intersection
     *
     * @return string
     */
    public function getIntersection()
    {
        return $this->intersection;
    }

    /**
     * Set postal
     *
     * @param string $postal
     *
     * @return Brochure
     */
    public function setPostal($postal)
    {
        $this->postal = $postal;

        return $this;
    }

    /**
     * Get postal
     *
     * @return string
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * Set address1
     *
     * @param string $address1
     *
     * @return Brochure
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return Brochure
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @return string
     */
    public function getEntityBaseRoute()
    {
        return 'brochure_admin_brochure_';
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

    /**
     * @return Brochure
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * @param Brochure $langParent
     * @return Brochure
     */
    public function setLangParent($langParent)
    {
        $this->langParent = $langParent;
        return $this;
    }

    /**
     * Set officeHeader
     *
     * @param string $officeHeader
     *
     * @return Brochure
     */
    public function setOfficeHeader($officeHeader)
    {
        $this->officeHeader = $officeHeader;

        return $this;
    }

    /**
     * Get officeHeader
     *
     * @return string
     */
    public function getOfficeHeader()
    {
        return $this->officeHeader;
    }

    /**
     * Set officeLine1
     *
     * @param string $officeLine1
     *
     * @return Brochure
     */
    public function setOfficeLine1($officeLine1)
    {
        $this->officeLine1 = $officeLine1;

        return $this;
    }

    /**
     * Get officeLine1
     *
     * @return string
     */
    public function getOfficeLine1()
    {
        return $this->officeLine1;
    }

    /**
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param Property $property
     * @return Brochure
     */
    public function setProperty($property)
    {
        $this->property = $property;
        return $this;
    }

    /**
     * Set officeLine2
     *
     * @param string $officeLine2
     *
     * @return Brochure
     */
    public function setOfficeLine2($officeLine2)
    {
        $this->officeLine2 = $officeLine2;

        return $this;
    }

    /**
     * Get officeLine2
     *
     * @return string
     */
    public function getOfficeLine2()
    {
        return $this->officeLine2;
    }

    /**
     * Get rejectComment
     *
     * @return string
     */
    public function getRejectComment()
    {
        return $this->rejectComment;
    }

    /**
     * Set rejectComment
     *
     * @param string $rejectComment
     *
     * @return Brochure
     */
    public function setRejectComment($rejectComment)
    {
        $this->rejectComment = $rejectComment;

        return $this;
    }

    /**
     * @return string
     */
    public function getRevertComment()
    {
        return $this->revertComment;
    }

    /**
     * @param string $revertComment
     * @return Brochure
     */
    public function setRevertComment($revertComment)
    {
        $this->revertComment = $revertComment;
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
            'Id' => 'id',
            'Language' => 'langId',
            'Name' => 'name',
            'Status' => 'status',
            'Version' => 'version',
            'Version Comment' => 'versionComment',
            'Rejection Comment' => 'rejectComment',
            'Created' => 'created',
            'Updated' => 'updated',
            'Deleted' => 'deletedAt',
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
     * Set heroImage
     *
     * @param \CraftKeen\BrochureBuilderBundle\Entity\BrochureImage $heroImage
     *
     * @return Brochure
     */
    public function setHeroImage(\CraftKeen\BrochureBuilderBundle\Entity\BrochureImage $heroImage = null)
    {
        $this->heroImage = $heroImage;

        return $this;
    }

    /**
     * Get heroImage
     *
     * @return \CraftKeen\BrochureBuilderBundle\Entity\BrochureImage
     */
    public function getHeroImage()
    {
        return $this->heroImage;
    }

    /**
     * Set contactImage
     *
     * @param \CraftKeen\BrochureBuilderBundle\Entity\BrochureImage $contactImage
     *
     * @return Brochure
     */
    public function setContactImage(\CraftKeen\BrochureBuilderBundle\Entity\BrochureImage $contactImage = null)
    {
        $this->contactImage = $contactImage;

        return $this;
    }

    /**
     * Get contactImage
     *
     * @return \CraftKeen\BrochureBuilderBundle\Entity\BrochureImage
     */
    public function getContactImage()
    {
        return $this->contactImage;
    }

    /**
     * Set contactLifestyleImage
     *
     * @param \CraftKeen\BrochureBuilderBundle\Entity\BrochureImage $contactLifestyleImage
     *
     * @return Brochure
     */
    public function setContactLifestyleImage(\CraftKeen\BrochureBuilderBundle\Entity\BrochureImage $contactLifestyleImage = null)
    {
        $this->contactLifestyleImage = $contactLifestyleImage;

        return $this;
    }

    /**
     * Get contactLifestyleImage
     *
     * @return \CraftKeen\BrochureBuilderBundle\Entity\BrochureImage
     */
    public function getContactLifestyleImage()
    {
        return $this->contactLifestyleImage;
    }

    /**
     * Set hideTagLine
     *
     * @param boolean $hideTagLine
     *
     * @return Brochure
     */
    public function setHideTagLine($hideTagLine)
    {
        $this->hideTagLine = $hideTagLine;

        return $this;
    }

    /**
     * Get hideTagLine
     *
     * @return boolean
     */
    public function getHideTagLine()
    {
        return $this->hideTagLine;
    }
}
