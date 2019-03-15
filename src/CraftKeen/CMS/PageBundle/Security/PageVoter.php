<?php

namespace CraftKeen\CMS\PageBundle\Security;

use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PageVoter extends Voter
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
     * PageVoter constructor.
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
        return [Page::class];
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

        // only vote on Page objects inside this voter
        if (!$subject instanceof Page) {
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

        // you know $subject is a Page object, thanks to supports
        /** @var Page $page */
        $page = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($page, $user, $token);
            case self::EDIT:
                return $this->canEdit($page, $user, $token);
            case self::CREATE:
                return $this->canCreate($user, $token);
            case self::DELETE:
                return $this->canDelete($page, $user, $token);
            case self::APPROVE:
                return $this->canApprove($page, $user, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param Page $page
     * @param User $user
     * @param string $access
     *
     * @return bool
     */
    protected function checkPageAccess(Page $page, User $user, $access)
    {
        $pageAccess = $page->getAccess();

        if (isset($pageAccess[$access]) && is_array($pageAccess[$access])) {
            $userRoles = $user->getRoles();
            foreach ($pageAccess[$access] as $pageRole) {
                if (in_array($pageRole, $userRoles)) {
                    return true;
                }
            }
        } else {
            return true;
        }

        return false;
    }

    /**
     * @param Page $page
     * @param User $user
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function canView(Page $page, User $user, TokenInterface $token)
    {
        // if they can edit, they can view
        if ($this->canEdit($page, $user, $token)) {
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
     * @param Page $page
     * @param User $user
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function canEdit(Page $page, User $user, TokenInterface $token)
    {
        if ($page->getStatus() == 'pending_approval') {
            return false;
        }

        $roles = [
            'ROLE_ADMINISTRATOR',
            'ROLE_SUPERADMINISTRATOR',
        ];
        if ($this->decisionManager->decide($token, $roles)) {
            return true;
        }

        if ($page->getId() == 1) {
            return false;
        }

        $roles = [
            'ROLE_EDITOR',
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return $this->checkPageAccess($page, $user, 'UPDATE');
        }

        return false;
    }

    /**
     * @param User $user
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function canCreate(User $user, TokenInterface $token)
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

        return false;
    }

    /**
     * @param $page
     * @param User $user
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function canDelete($page, User $user, TokenInterface $token)
    {
        if ($page->getId() == 1) {
            return false;
        }

        $roles = [
            'ROLE_ADMINISTRATOR',
            'ROLE_SUPERADMINISTRATOR',
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return true;
        }

        if ($page->getStatus() == 'draft' && $page->getCreatedBy() == $user) {
            return true;
        }

        return false;
    }

    /**
     * @param $page
     * @param User $user
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function canApprove($page, User $user, TokenInterface $token)
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
            return $this->checkPageAccess($page, $user, 'APPROVE');
        }

        return false;
    }
}
