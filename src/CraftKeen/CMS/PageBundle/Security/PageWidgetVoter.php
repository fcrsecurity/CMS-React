<?php
namespace CraftKeen\CMS\PageBundle\Security;

use CraftKeen\CMS\PageBundle\Entity\PageWidget;
use CraftKeen\CMS\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PageWidgetVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW        = 'canview';
    const EDIT        = 'canedit';
    const CREATE    = 'cancreate';
    const DELETE    = 'candelete';
    const APPROVE    = 'canapprove';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function getSupportedClasses()
    {
        return [PageWidget::class];
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::CREATE, self::DELETE, self::APPROVE))) {
            return false;
        }

        // only vote on Page objects inside this voter
        if (!$subject instanceof PageWidget) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Page object, thanks to supports
        /** @var Page $page */
        $pageWidget = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($pageWidget, $user, $token);
            case self::EDIT:
                return $this->canEdit($pageWidget, $user, $token);
            case self::CREATE:
                return $this->canCreate($user, $token);
            case self::DELETE:
                return $this->canDelete($pageWidget, $user, $token);
            case self::APPROVE:
                return $this->canApprove($pageWidget, $user, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(PageWidget $pageWidget, User $user, TokenInterface $token)
    {
        // if they can edit, they can view
        if ($this->canEdit($pageWidget, $user, $token)) {
            return true;
        }

        $roles = [
            'ROLE_APPROVER',
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return true;
        }

        return false;
    }

    private function canEdit(PageWidget $pageWidget, User $user, TokenInterface $token)
    {
        if ($pageWidget->getStatus() == 'pending_approval'){
            return false;
        }

        $roles = [
            'ROLE_ADMINISTRATOR',
            'ROLE_SUPERADMINISTRATOR',
            'ROLE_EDITOR',
            'ROLE_CONTRIBUTOR',
            //'ROLE_APPROVER'
        ];
        if ($this->decisionManager->decide($token, $roles)) {
            return true;
        }
        if ($pageWidget->getPage()->getId() == 1){
            return false;
        }

        return false;
    }

    protected function canCreate(User $user, TokenInterface $token)
    {
        $roles = [
            'ROLE_ADMINISTRATOR',
            'ROLE_SUPERADMINISTRATOR',
            'ROLE_EDITOR',
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return true;
        }

        return false;
    }

    protected function canDelete($pageWidget, User $user, TokenInterface $token)
    {
        $roles = [
            'ROLE_ADMINISTRATOR',
            'ROLE_SUPERADMINISTRATOR',
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return true;
        }

        if ($pageWidget->getPage()->getId() == 1){
            return false;
        }

        return false;
    }

    protected function canApprove($pageWidget, User $user, TokenInterface $token)
    {
        $roles = [
            'ROLE_ADMINISTRATOR',
            'ROLE_SUPERADMINISTRATOR',
            'ROLE_APPROVER'
        ];

        if ($this->decisionManager->decide($token, $roles)) {
            return true;
        }

        return false;
    }
}
