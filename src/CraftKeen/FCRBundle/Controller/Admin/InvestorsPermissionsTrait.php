<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\FCRBundle\Entity\InvestorsEntitiesPermissionsTrait;

/**
 * Trait InvestorsPermissionsTrait
 * This Trait meant to holds a specific methods for all Classes in Investors Section.
 *
 * @package CraftKeen\FCRBundle\Controller\Admin
 */
trait InvestorsPermissionsTrait
{
    use InvestorsEntitiesPermissionsTrait;

    /**
     * setEntityDefaults
     *
     * @param $entityName
     * @return mixed
     */
    public function setEntityDefaults($entityName)
    {
        $defaultAccess = $this->getDefaultAccess();

        $site = $this->getDoctrine()
            ->getManager()
            ->getRepository(Site::class)
            ->findOneBy(['id' => 1]); //TODO: replace hard-coded '1'

        $object = new $entityName();
        $object->setSite($site);
        $object->setAccess($defaultAccess);

        return $object;
    }
}