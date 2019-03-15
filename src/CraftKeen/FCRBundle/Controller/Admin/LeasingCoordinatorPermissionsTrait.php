<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\FCRBundle\Entity\LeasingCoordinatorEntitiesPermissionsTrait;

/**
 * Trait LeasingPermissionsTrait
 * This Trait meant to holds a specific methods for all Classes in Leasing Section.
 * Can be used with Controllers
 *
 * @package CraftKeen\FCRBundle\Controller\Admin
 */
trait LeasingCoordinatorPermissionsTrait
{
    use LeasingCoordinatorEntitiesPermissionsTrait;

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