<?php

namespace CraftKeen\CMS\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CraftKeen\CMS\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_CONTRIBUTOR = 'ROLE_CONTRIBUTOR';
    const ROLE_EDITOR = 'ROLE_EDITOR';
    const ROLE_APPROVER = 'ROLE_APPROVER';
    const ROLE_ADMINISTRATOR = 'ROLE_ADMINISTRATOR';
    const ROLE_SUPERADMINISTRATOR = 'ROLE_SUPERADMINISTRATOR';

    // FCR Specific Roles
    const ROLE_INVESTORS = 'ROLE_INVESTORS';
    const ROLE_LEASING = 'ROLE_LEASING';
    const ROLE_LEASING_REGIONAL_COORDINATORS = 'ROLE_LEASING_REGIONAL_COORDINATORS';
    const ROLE_HR = 'ROLE_HR';

    //Brochure roles
    const ROLE_BROCHURE_ADMINISTRATOR = 'ROLE_BROCHURE_ADMINISTRATOR';
    const ROLE_BROCHURE_EDITOR = 'ROLE_BROCHURE_EDITOR';
    const ROLE_BROCHURE_APPROVER = 'ROLE_BROCHURE_APPROVER';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;    
    
    /**
     * @Assert\Regex(
     *  pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,}/",
     *  message="Password must be 8 or more characters long and contain at least one digit, one upper- and one lowercase character."
     * )
     */
    protected $plainPassword;
}
