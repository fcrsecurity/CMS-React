<?php

namespace CraftKeen\CMS\AdminBundle\Security;

use CraftKeen\CMS\AdminBundle\Entity\Inbox;
use CraftKeen\CMS\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class InboxVoter extends AdminVoter
{
    /**
     * @param $post
     * @param User $user
     *
     * @return bool
     */
    protected function canView($post, User $user)
    {
        if (!$post instanceof Inbox) {
            // View Listings
            return true;
        } else {
            // if they can edit, they can view
            if ($this->canEdit($post, $user)) {
                return true;
            }

            // the Post object could have, for example, a method isPrivate()
            // that checks a boolean $private property
            return $user === $post->getRecipient();
        }
    }

    /**
     * @param $post
     * @param User $user
     *
     * @return bool
     */
    protected function canEdit($post, User $user)
    {
        if (!$post instanceof Inbox) {
            // throw new \LogicException('The Instanse must to be Inbox');
            return false;
        }

        return $user === $post->getRecipient();
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
