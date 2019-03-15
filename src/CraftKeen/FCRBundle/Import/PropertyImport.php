<?php

namespace CraftKeen\FCRBundle\Import;

use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\FCRBundle\Entity\Manager;
use CraftKeen\FCRBundle\Entity\Property;
use CraftKeen\FCRBundle\Entity\PropertyGallery;
use CraftKeen\FCRBundle\Entity\PropertyDemographic;
use CraftKeen\FCRBundle\Entity\PropertyDetails;
use CraftKeen\FCRBundle\Entity\PropertyFilter;
use CraftKeen\FCRBundle\Entity\PropertyVacancy;
use CraftKeen\FCRBundle\Entity\Tenant;
use Doctrine\ORM\EntityManager;
use CraftKeen\CMS\UserBundle\Entity\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Config\Definition\Exception\Exception;

class PropertyImport extends AbstractImport
{
    private $libraryUrl = 'https://fcr.ca/uploads/';

    /**
     * PressReleaseImport constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * Clean up tables
     *
     * @return void
     */
    public function eraseTables()
    {
    }

    /**
     * Load Dependent Tables
     *
     * @return array
     */
    public function loadDependencies()
    {
        if (!isset($this->records['managers']) ||
            !isset($this->records['tenants'])) {
            throw new Exception('Dependencies was not loaded properly');
        }

        $response['managers'] = $this->loadManagers($this->records['managers']);
        $response['tenants'] = $this->loadTenants($this->records['tenants']);

        return $response;
    }

    /**
     * Load Properties
     *
     * @return array
     */
    public function loadProperties()
    {
        if (!isset($this->records['properties']) ||
            !isset($this->records['propertiesManagers']) ||
            !isset($this->records['propertiesGallery']) ||
            !isset($this->records['propertiesTenants'])) {
            throw new Exception('Dependencies for loading propeties not sutisfied!');
        }

        $this->em->getRepository(Property::class)->truncate();

        $response['properties'] = $this->loadPropertyData($this->records['properties']);
        $response['propertiesGallery'] = $this->loadPropertyGallery($this->records['propertiesGallery']);
        $response['propertiesManagers'] = $this->loadPropertyManagers($this->records['propertiesManagers']);
        $response['propertiesTenants'] = $this->loadPropertyTenants($this->records['propertiesTenants']);

        return $response;
    }

    /**
     * Load managers
     *
     * @param array $items
     *
     * @return int
     */
    private function loadManagers($items)
    {
        $this->em->getRepository(Manager::class)->truncate();

        $count = 0;

        $createdBy = $updatedBy = $this->em->getRepository(User::class)->findOneById(1);

        foreach ($items as $key => $item) {
            $manager = new Manager();
            $manager->setId($item['id']);

            $manager->setCreatedBy($createdBy);
            $manager->setUpdatedBy($updatedBy);
            $manager->setType($item['type']);
            $manager->setImage(
                $this->libraryUrl . 'properties/leasing-reps-headshots/' . $this->processField($item['image'])
            );
            $manager->setImageAlt('Headshot ' . $item['first_name'] . ' ' . $item['last_name']);
            $manager->setFirstName($this->processField($item['first_name']));
            $manager->setLastName($this->processField($item['last_name']));
            $manager->setTitle($this->processField($item['title']));
            $manager->setEmail($this->processField($item['email']));
            $manager->setFax($this->processField($item['fax']));
            $manager->setPhone($this->processField($item['phone']));
            $manager->setPhoneExtension($this->processField($item['phone_extension']));
            $manager->setTollfree($this->processField($item['tollfree']));
            $manager->setLang($this->lang['en']);
            $manager->setSortOrder(self::SORT_ORDER);
            $manager->setStatus('live');
            $manager->setVersion(1);
            $manager->setVersionComment('Initial');
            $this->em->persist($manager);
            $metadata = $this->em->getClassMetaData(get_class($manager));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $this->em->flush();
            $count++;
        }

        $highest_id = $this->em->createQueryBuilder()
            ->select('MAX(e.id)')
            ->from(Manager::class, 'e')
            ->getQuery()
            ->getSingleScalarResult();

        // Translate
        foreach ($items as $key => $item) {
            $highest_id++;
            /** @var Manager $manager */
            $manager = $this->em->getRepository(Manager::class)->findOneById((int)$item['id']);
            $managerFr = clone $manager;
            $managerFr->setLang($this->lang['fr']);
            $managerFr->setId($highest_id);
            $managerFr->setLangParent($manager);
            if (null !== $this->processField($item['first_name_fr'])) {
                $managerFr->setFirstName($this->processField($item['first_name_fr']));
            }

            if (null !== $this->processField($item['last_name_fr'])) {
                $managerFr->setLastName($this->processField($item['last_name_fr']));
            }

            if (null !== $this->processField($item['title_fr'])) {
                $managerFr->setTitle($this->processField($item['title_fr']));
            }
            $this->em->persist($managerFr);
            $this->em->flush();
        }

        return $count;
    }

    /**
     * Load Tenants
     *
     * @param array $items
     *
     * @return int
     */
    private function loadTenants($items)
    {
        $this->em->getRepository(Tenant::class)->truncate();

        $count = 0;
        $createdBy = $this->em->getRepository(User::class)->findOneById(1);
        $updatedBy = $this->em->getRepository(User::class)->findOneById(1);

        foreach ($items as $item) {
            $tenant = new Tenant();
            $tenant->setId($item['id']);
            $tenant->setCreatedBy($createdBy);
            $tenant->setUpdatedBy($updatedBy);
            $tenant->setLang($this->lang['en']);
            $tenant->setStatus('live');
            $tenant->setImage($this->libraryUrl . 'tenants/' . $item['image']);
            $tenant->setImageAlt($item['name'] . ' Logo');
            $tenant->setName($item['name']);
            $tenant->setSortOrder(self::SORT_ORDER);
            $this->em->persist($tenant);
            $metadata = $this->em->getClassMetaData(get_class($tenant));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $this->em->flush();
            $count++;
        }

        return $count;
    }

    public function translate()
    {
        $count = 0;
        foreach ($this->records['properties'] as $item) {
            $p = $this->em->getRepository(Property::class)->findOneById((int)$item['id']);
            $t = $this->em->getRepository(Property::class)->findOneByLangParent((int)$item['id']);

            if (!$t) {
                throw new Exception(
                    'No Property found for id ' . $item['id'] . '. Translate function'
                );
            }

            foreach ($p->getManagers() as $m) {
                $mt = $this->em->getRepository(Manager::class)
                    ->findOneBy(['lang' => $this->lang['fr'], 'langParent' => $m]);
                if ($mt) {
                    $t->addManager($mt);
                }
            }

            foreach ($p->getTenants() as $m) {
                $t->addTenant($m);
            }

            // Update Filters relation
            $propertyFilter = new PropertyFilter();
            $propertyFilter->setProperty($t);
            $propertyFilter->setIsFilterGroceryAnchored($item['filter_grocery_anchored']);
            $propertyFilter->setIsFilterUrbanRetail($item['filter_urban_retail']);
            $propertyFilter->setIsFilterOfficeSpace($item['filter_office_space']);
            $propertyFilter->setIsFilterUnderDevelopment($item['filter_under_development']);
            $this->em->persist($propertyFilter);

            if (null !== $this->processField($item['parent_name_fr'])) {
                $t->setParentName($this->processField($item['parent_name_fr']));
            }
            if (null !== $this->processField($item['child_name_fr'])) {
                $t->setChildName($this->processField($item['child_name_fr']));
            }

            # Add Property Details
            $propertyDetails = new PropertyDetails();
            $propertyDetails->setProperty($t);
            $propertyDetails->setHeroImage($this->processField($item['hero_image']));
            $propertyDetails->setDescription(
                $this->processField(($item['description_fr']) ? $item['description_fr'] : $item['description'])
            );
            $propertyDetails->setLeedDescription(
                $this->processField(
                    ($item['leed_description_fr']) ? $item['leed_description_fr'] : $item['leed_description']
                )
            );
            $propertyDetails->setMarketingPdf($this->processField($item['marketing_pdf']));
            $propertyDetails->setSitePlanPdf($this->processField($item['siteplan_name']));
            $propertyDetails->setGeoAddress1(
                $this->processField(
                    ($item['geo_address_1_fr']) ? $item['geo_address_1_fr'] : $item['geo_address_1']
                )
            );
            $propertyDetails->setGeoAddress2(
                $this->processField(
                    ($item['geo_address_2_fr']) ? $item['geo_address_2_fr'] : $item['geo_address_2']
                )
            );
            $propertyDetails->setGeoIntersetion(
                $this->processField(
                    ($item['geo_intersetion_fr']) ? $item['geo_intersetion_fr'] : $item['geo_intersetion']
                )
            );
            $propertyDetails->setGeoCity(
                $this->processField(($item['geo_city_fr']) ? $item['geo_city_fr'] : $item['geo_city'])
            );
            $propertyDetails->setGeoProvince($this->processField($item['geo_province']));
            $propertyDetails->setGeoProvinceRegion($this->processField($item['geo_province_region']));
            $propertyDetails->setGeoCountry($this->processField($item['geo_country']));
            $propertyDetails->setGeoPostal($this->processField($item['geo_postal']));
            $propertyDetails->setGeoLat($this->processField($item['geo_lat']));
            $propertyDetails->setGeoLng($this->processField($item['geo_lng']));
            $propertyDetails->setSqft($this->processField($item['sqft']));
            $propertyDetails->setVideoUrl($this->processField($item['video_url']));
            $propertyDetails->setSocialFacebook($this->processField($item['social_facebook']));
            $propertyDetails->setSocialTwitter($this->processField($item['social_twitter']));
            $propertyDetails->setSocialUrl($this->processField($item['social_url']));
            $propertyDetails->setVacantSqft($this->processField($item['vacant_sqft']));
            $propertyDetails->setSeoTitle(null);
            $propertyDetails->setSeoDescription(null);
            $propertyDetails->setSeoKeywords(null);
            $propertyDetails->setSeoIsIndex(true);
            $this->em->persist($propertyDetails);

            $this->em->persist($t);
            $this->em->flush($t);

            $count++;
        }

        return $count;
    }

    /**
     * Load Properties Data, Demographics, Vacancy and Location
     *
     * @param array $items
     *
     * @return int
     */
    private function loadPropertyData($items)
    {
        $access = [
            'CREATE' => null,
            'READ' => null,
            'UPDATE' =>
                [
                    'ROLE_LEASING',
                ],
            'DELETE' => null,
            'APPROVE' =>
                [
                    'ROLE_LEASING',
                ],
        ];

        $access = serialize($access);

        $site = $this->em->getRepository(Site::class)->findOneBy(['isMain' => 1]);

        $count = 0;
        foreach ($items as $item) {
            # Add Property Data
            $property = new Property();
            $property->setCode($item['code']);
            $property->setStatus('live');
            $property->setParentName($item['title']);
            $property->setChildName($item['category']);
            $property->setThumbnail($item['thumbnail']);
            $property->setIsVacant($item['is_vacant']);
            $property->setIsGreen($item['is_green']);
            $property->setIsBoma($item['is_boma']);
            $property->setIsHidden($item['hidden']);
            $property->setLang($this->lang['en']);
            $property->setSortOrder($item['sort_order']);
            $property->setCreatedBy($this->user);
            $property->setCreated(new \DateTime());
            $property->setVersion(1);
            $property->setVersionComment("Initial");
            $property->setAccess($access);
            $property->setSite($site);
            $property->setSortOrder($count);
            $this->em->persist($property);
            $metadata = $this->em->getClassMetaData(get_class($property));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

            # Add Property Details
            $propertyDetails = new PropertyDetails();
            $propertyDetails->setProperty($property);
            $propertyDetails->setHeroImage($this->processField($item['hero_image']));
            $propertyDetails->setDescription($this->processField($item['description']));
            $propertyDetails->setLeedDescription($this->processField($item['leed_description']));
            $propertyDetails->setMarketingPdf($this->processField($item['marketing_pdf']));
            $propertyDetails->setSitePlanPdf($this->processField($item['siteplan_name']));
            $propertyDetails->setGeoAddress1($this->processField($item['geo_address_1']));
            $propertyDetails->setGeoAddress2($this->processField($item['geo_address_2']));
            $propertyDetails->setGeoIntersetion($this->processField($item['geo_intersetion']));
            $propertyDetails->setGeoCity($this->processField($item['geo_city']));
            $propertyDetails->setGeoProvince($this->processField($item['geo_province']));
            $propertyDetails->setGeoProvinceRegion($this->processField($item['geo_province_region']));
            $propertyDetails->setGeoCountry($this->processField($item['geo_country']));
            $propertyDetails->setGeoPostal($this->processField($item['geo_postal']));
            $propertyDetails->setGeoLat($this->processField($item['geo_lat']));
            $propertyDetails->setGeoLng($this->processField($item['geo_lng']));
            $propertyDetails->setSqft($this->processField($item['sqft']));
            $propertyDetails->setVideoUrl($this->processField($item['video_url']));
            $propertyDetails->setSocialFacebook($this->processField($item['social_facebook']));
            $propertyDetails->setSocialTwitter($this->processField($item['social_twitter']));
            $propertyDetails->setSocialUrl($this->processField($item['social_url']));
            $propertyDetails->setVacantSqft($this->processField($item['vacant_sqft']));
            $propertyDetails->setSeoTitle($item['title']);
            $propertyDetails->setSeoDescription(null);
            $propertyDetails->setSeoKeywords(null);
            $propertyDetails->setSeoIsIndex(true);
            $this->em->persist($propertyDetails);

            // Update Demographic relation
            $propertyDemographic = new PropertyDemographic();
            $propertyDemographic->setProperty($property);
            $propertyDemographic->setAnnualAverageDailyTraffic((float)$item['annual_average_daily_traffic']);
            $propertyDemographic->setPopulation1km($item['population_1km']);
            $propertyDemographic->setPopulation3km($item['population_3km']);
            $propertyDemographic->setPopulation5km($item['population_5km']);
            $propertyDemographic->setHousehold1km($item['household_1km']);
            $propertyDemographic->setHousehold3km($item['household_3km']);
            $propertyDemographic->setHousehold5km($item['household_5km']);
            $propertyDemographic->setHouseholdIncome1km($item['household_income_1km']);
            $propertyDemographic->setHouseholdIncome3km($item['household_income_3km']);
            $propertyDemographic->setHouseholdIncome5km($item['household_income_5km']);
            $this->em->persist($propertyDemographic);

            // Update Filters relation
            $propertyFilter = new PropertyFilter();
            $propertyFilter->setProperty($property);
            $propertyFilter->setIsFilterGroceryAnchored($item['filter_grocery_anchored']);
            $propertyFilter->setIsFilterUrbanRetail($item['filter_urban_retail']);
            $propertyFilter->setIsFilterOfficeSpace($item['filter_office_space']);
            $propertyFilter->setIsFilterUnderDevelopment($item['filter_under_development']);
            $this->em->persist($propertyFilter);

            # Add Vacancies

            $vCount = 1;
            for ($i = 1; $i < 11; $i++) {
                if ((int)$item['vacancy_' . $i] > 0) {
                    $propertyVacancy = new PropertyVacancy();
                    //$propertyVacancy->setName('Vacancy-' . $vCount);
                    $propertyVacancy->setVacantSqft((int)$item['vacancy_' . $i]);
                    $propertyVacancy->setProperty($property);
                    $this->em->persist($propertyVacancy);
                    $vCount++;
                }
            }

            $count++;

            // translate
            $t = clone $property;
            $t->setLang($this->lang['fr']);
            $t->setLangParent($property);
            $this->em->persist($t);
        }

        $this->em->flush();
        $this->em->clear();
        return $count;
    }

    /**
     * Load Property Gallery
     *
     * @param array $items
     *
     * @return int
     */
    private function loadPropertyGallery($items)
    {
        $count = 0;
        foreach ($items as $item) {

            /** @var Property $property */
            $property = $this->em->getRepository(Property::class)->findOneById((int)$item['property_id']);
            if (!$property) {
                throw new Exception(
                    'No Property found for id ' . $item['property_id']
                );
            }
            $propertyGallery = new PropertyGallery();
            $propertyGallery->setProperty($property);
            $propertyGallery->setImage($item['path']);
            $propertyGallery->setImageAlt($property->getParentName() . ' ' . $count);
            $propertyGallery->setSortOrder(self::SORT_ORDER);
            $this->em->persist($propertyGallery);

            $count++;
        }
        $this->em->flush();
        $this->em->clear();

        return $count;
    }

    /**
     * Load Property Gallery
     *
     * @param array $items
     *
     * @return int
     */
    private function loadPropertyManagers($items)
    {
        $count = 0;
        foreach ($items as $item) {
            /** @var Property $property */
            $property = $this->em->getRepository(Property::class)->findOneById((int)$item['property_id']);
            $manager = $this->em->getRepository(Manager::class)->findOneById((int)$item['contact_id']);
            if (!$property) {
                throw new Exception(
                    'No Property found for id ' . $item['property_id']
                );
            }
            if (!$manager) {
                throw new Exception(
                    'No Manager found for id ' . $item['contact_id']
                );
            }
            $property->addManager($manager);
            $this->em->flush($property);
            $count++;
        }
        $this->em->clear();

        return $count;
    }

    /**
     * Load Property Gallery
     *
     * @param array $items
     *
     * @return int
     */
    private function loadPropertyTenants($items)
    {
        $count = 0;
        foreach ($items as $item) {
            /** @var Property $property */
            $property = $this->em->getRepository(Property::class)->findOneById((int)$item['property_id']);
            $tenant = $this->em->getRepository(Tenant::class)->findOneById((int)$item['tenant_id']);
            if (!$property) {
                throw new Exception(
                    'No Property found for id ' . $item['property_id']
                );
            }
            if (!$tenant) {
                throw new Exception(
                    'No Tenant found for id ' . $item['tenant_id']
                );
            }
            $property->addTenant($tenant);
            $this->em->flush($property);
            $count++;
        }
        $this->em->clear();

        return $count;
    }
}
