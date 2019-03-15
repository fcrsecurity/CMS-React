<?php

namespace CraftKeen\FCRBundle\Security;

use CraftKeen\CMS\MenuBundle\Entity\Menu;

use CraftKeen\FCRBundle\Entity\ActiveProvince;
use CraftKeen\FCRBundle\Entity\Manager;
use CraftKeen\FCRBundle\Entity\Tenant;
use CraftKeen\FCRBundle\Entity\Property;
use CraftKeen\FCRBundle\Entity\PropertySubmission;
use CraftKeen\FCRBundle\Entity\PropertyFeatureSlider;

use CraftKeen\FCRBundle\Entity\PressRelease;
use CraftKeen\FCRBundle\Entity\FinancialReport;
use CraftKeen\FCRBundle\Entity\FAQ;
use CraftKeen\FCRBundle\Entity\AnalystCoverage;
use CraftKeen\FCRBundle\Entity\Dividend;
use CraftKeen\FCRBundle\Entity\Debenture;
use CraftKeen\FCRBundle\Entity\ConferenceCall;
use CraftKeen\FCRBundle\Entity\People;

use CraftKeen\FCRBundle\Entity\CareersSlider;
use CraftKeen\FCRBundle\Entity\CareersEmployee;
use CraftKeen\FCRBundle\Entity\RetailArt;
use CraftKeen\FCRBundle\Entity\RetailArtCategory;
use CraftKeen\FCRBundle\Entity\RetailArtGallery;
use CraftKeen\FCRBundle\Entity\Sustainability;
use CraftKeen\FCRBundle\Entity\SustainabilitySlider;

use CraftKeen\FCRBundle\Entity\Office;

use CraftKeen\CMS\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FCRVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'canview';
    const EDIT = 'canedit';
    const CREATE = 'cancreate';
    const DELETE = 'candelete';
    const APPROVE = 'canapprove';

    /** @var AccessDecisionManagerInterface */
    protected $decisionManager;

    /**
     * ActiveProvinceVoter constructor.
     *
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * @return array
     */
    protected function getSupportedClasses()
    {
        return [
            Menu::class,

            ActiveProvince::class,
            Manager::class,
            Tenant::class,
            Property::class,
            PropertySubmission::class,
            PropertyFeatureSlider::class,

            PressRelease::class,
            FinancialReport::class,
            FAQ::class,
            AnalystCoverage::class,
            Dividend::class,
            Debenture::class,
            ConferenceCall::class,
            People::class,

            CareersSlider::class,
            CareersEmployee::class,
            RetailArt::class,
            RetailArtCategory::class,
            RetailArtGallery::class,
            Sustainability::class,
            SustainabilitySlider::class,

            Office::class,
        ];
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::CREATE, self::DELETE, self::APPROVE])) {
            return false;
        }

        // Only for this entities
        if (!$subject instanceof Menu &&
            !$subject instanceof ActiveProvince &&
            !$subject instanceof Manager &&
            !$subject instanceof Tenant &&
            !$subject instanceof Property &&
            !$subject instanceof PropertySubmission &&
            !$subject instanceof PropertyFeatureSlider &&

            !$subject instanceof PressRelease &&
            !$subject instanceof FinancialReport &&
            !$subject instanceof FAQ &&
            !$subject instanceof AnalystCoverage &&
            !$subject instanceof Dividend &&
            !$subject instanceof Debenture &&
            !$subject instanceof ConferenceCall &&
            !$subject instanceof People &&

            !$subject instanceof Sustainability &&
            !$subject instanceof SustainabilitySlider &&
            !$subject instanceof CareersSlider &&
            !$subject instanceof CareersEmployee &&
            !$subject instanceof RetailArt &&
            !$subject instanceof RetailArtGallery &&
            !$subject instanceof RetailArtCategory &&
            !$subject instanceof Office
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($subject, $user, $token);
            case self::EDIT:
                return $this->canEdit($subject, $user, $token);
            case self::CREATE:
                return $this->canCreate($subject, $user, $token);
            case self::DELETE:
                return $this->canDelete($subject, $user, $token);
            case self::APPROVE:
                return $this->canApprove($subject, $user, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param $subject
     * @param User $user
     * @param string $access
     *
     * @return bool
     * @internal param $page
     */
    protected function checkSubjectAccess($subject, User $user, $access)
    {
        $subjectAccess = $subject->getAccess();
        $subjectAccess = unserialize($subjectAccess);

        if (is_array($subjectAccess) && isset($subjectAccess[$access])) {
            $userRoles = $user->getRoles();
            foreach ($subjectAccess[$access] as $subjectRole) {
                if (in_array($subjectRole, $userRoles)) {
                    return true;
                }
            }
        } else {
            return true;
        }

        return false;
    }

    /**
     * @param $subject
     * @param User $user
     * @param TokenInterface $token
     *
     * @return bool
     * @internal param $page
     */
    protected function canView($subject, User $user, TokenInterface $token)
    {
        // if they can edit, they can view
        if ($this->canEdit($subject, $user, $token)) {
            return true;
        }

        $roles = [
            'ROLE_APPROVER',
            'ROLE_CONTRIBUTOR'
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return true;
        }

        return false;
    }

    /**
     * @param $subject
     * @param User $user
     * @param TokenInterface $token
     *
     * @return bool
     * @internal param $page
     */
    protected function canEdit($subject, User $user, TokenInterface $token)
    {
        if ($subject->getStatus() == 'pending_approval') {
            return false;
        }

        $roles = [
            'ROLE_ADMINISTRATOR',
            'ROLE_SUPERADMINISTRATOR',
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return true;
        }

        $roles = [
            'ROLE_EDITOR',
            'ROLE_CONTRIBUTOR',
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return $this->checkSubjectAccess($subject, $user, 'UPDATE');
        }

        $roles = [
            'ROLE_LEASING_REGIONAL_COORDINATORS',
        ];

        if (($subject instanceof Tenant ||
                $subject instanceof Manager ||
                $subject instanceof Property ||
                $subject instanceof PropertyFeatureSlider) &&
            $this->decisionManager->decide($token, $roles)) {
            return $this->checkSubjectAccess($subject, $user, 'UPDATE');
        }

        return false;
    }

    /**
     * @param User $user
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function canCreate($subject, User $user, TokenInterface $token)
    {
        $roles = [
            'ROLE_ADMINISTRATOR',
            'ROLE_SUPERADMINISTRATOR',
            'ROLE_CONTRIBUTOR',
            'ROLE_EDITOR',
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return true;
        }

        $roles = [
            'ROLE_LEASING_REGIONAL_COORDINATORS'
        ];

        if (($subject instanceof Property ||
                $subject instanceof Tenant ||
                $subject instanceof Manager) &&
            $this->decisionManager->decide($token, $roles)) {
            return true;
        }

        return false;
    }

    /**
     * @param $subject
     * @param User $user
     * @param TokenInterface $token
     *
     * @return bool
     * @internal param $page
     */
    protected function canDelete($subject, User $user, TokenInterface $token)
    {
        $roles = [
            'ROLE_ADMINISTRATOR',
            'ROLE_SUPERADMINISTRATOR',
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return true;
        }

        return false;
    }

    /**
     * @param $subject
     * @param User $user
     * @param TokenInterface $token
     *
     * @return bool
     * @internal param $page
     */
    protected function canApprove($subject, User $user, TokenInterface $token)
    {
        $roles = [
            'ROLE_ADMINISTRATOR',
            'ROLE_SUPERADMINISTRATOR',
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return true;
        }

        $roles = [
            'ROLE_APPROVER',
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return $this->checkSubjectAccess($subject, $user, 'APPROVE');
        }

        return false;
    }
}
