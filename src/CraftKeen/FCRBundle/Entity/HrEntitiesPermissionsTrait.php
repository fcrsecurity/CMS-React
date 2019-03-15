<?php

namespace CraftKeen\FCRBundle\Entity;

use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\CMS\UserBundle\Entity\User;

/**
 * Trait HrPermissionsTrait
 * This Trait meant to holds a specific methods for all Classes in HR Section.
 *
 * @package CraftKeen\FCRBundle\Controller\Admin
 */
trait HrEntitiesPermissionsTrait
{
    /**
     * Apply Custom Permissions Access for this group of Entities.
     *
     * @return string
     */
    public function getDefaultAccess()
    {
        return serialize([
            'CREATE' => null,
            'READ' => null,
            'UPDATE' => [User::ROLE_HR],
            'DELETE' => null,
            'APPROVE' => [User::ROLE_HR],
        ]);
    }
}