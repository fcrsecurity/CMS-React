<?php

namespace CraftKeen\Bundle\WidgetBundle\Model;

use CraftKeen\Bundle\ComponentBundle\Model\SecurityContextAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\SecurityContextAwareTrait;
use CraftKeen\CMS\UserBundle\Entity\User;

class TitleWidget extends AbstractWidget implements SecurityContextAwareInterface
{
    use SecurityContextAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getUid()
    {
        return sprintf("%s-%s", parent::getUid(), $this->getUser() ? 'logged' : 'anonymous');
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        return array_merge(parent::getTemplateData(), ['logged' => $this->getUser() ? true : false]);
    }

    /**
     * @return User|null
     */
    protected function getUser()
    {
        $user = $this->getToken()->getUser();
        if ($user instanceof User) {
            return $user;
        }

        return null;
    }
}
