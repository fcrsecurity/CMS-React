<?php

namespace CraftKeen\CMS\AdminBundle\Security;

use CraftKeen\CMS\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdminVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected $decisionManager;

    /**
     * AdminVoter constructor.
     *
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
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
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])) {
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
        // ROLE_SUPERADMINISTRATOR can do anything! The power!
        if ($this->decisionManager->decide($token, ['ROLE_SUPERADMINISTRATOR'])) {
            return true;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /** @var $post */
        $post = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($post, $user);
            case self::EDIT:
                return $this->canEdit($post, $user);
            case self::DELETE:
                return $this->canDelete($post, $user, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param $post
     * @param User $user
     *
     * @return bool
     */
    protected function canView($post, User $user)
    {
        return false;
    }

    /**
     * @param $post
     * @param User $user
     *
     * @return bool
     */
    protected function canEdit($post, User $user)
    {
        return false;
    }

    /**
     * @param $post
     * @param User $user
     *
     * @return bool
     */
    protected function canDelete($post, User $user, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_ADMINISTRATOR'])) {
            return true;
        }

        return false;
    }
}
