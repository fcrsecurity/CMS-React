<?php

namespace CraftKeen\CMS\UserBundle\Tests\Functional\Fixtures;

use CraftKeen\CMS\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends AbstractFixture
{
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@example.com')
            ->setEnabled(true)
            ->setPassword('123456')
            ->setSuperAdmin(true)
            ->setUsername('user');
        $manager->persist($user);
        $this->addReference('default_user', $user);
        $manager->flush();
    }

}
