<?php
/**
 * Created by PhpStorm.
 * User: andreykopkin
 * Date: 24.11.17
 * Time: 16:11
 */

namespace Tests\BrochureBuilderBundle\Repository;

use CraftKeen\BrochureBuilderBundle\Entity\FileManagerObject;
use CraftKeen\BrochureBuilderBundle\Repository\FileManagerObjectRepository;
use CraftKeen\CMS\UserBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileManagerObjectRepositoryTest extends WebTestCase
{

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * Load kernel and perform setup
     */
    protected function setup() {
        //start the symfony kernel
        $kernel = static::createKernel();
        $kernel->boot();

        //get the DI container
        $container = $kernel->getContainer();
        $this->doctrine = $container->get('doctrine');
    }

    public function prepareDatabase() {
        // Clear database
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $cmd = $em->getClassMetadata(FileManagerObject::class);
        $dbPlatform = $connection->getDatabasePlatform();
        $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
        $connection->executeUpdate($q);

        $user = $em->getRepository(User::class)->find(2);
        //create entities
        $entity1 = new FileManagerObject();
        $entity1->setCreatedAt(new \DateTime());
        $entity1->setOwner($user);
        $entity1->setName('testName1');
        $entity1->setPath('testPath1');
        $entity1->setMime('unknown');
        $entity1->setMetaData('test meta1');
        $em->persist($entity1);

        $entity2 = new FileManagerObject();
        $entity2->setCreatedAt(new \DateTime());
        $entity2->setOwner($user);
        $entity2->setName('testName2');
        $entity2->setPath('testPath2');
        $entity2->setMime('unknown');
        $entity2->setMetaData('test meta2');
        $em->persist($entity2);

        $entity3 = new FileManagerObject();
        $entity3->setCreatedAt(new \DateTime());
        $entity3->setOwner($user);
        $entity3->setName('testName3');
        $entity3->setPath('testPath3');
        $entity3->setMime('unknown');
        $entity3->setMetaData('test meta3');
        $em->persist($entity3);
        $em->flush();
    }

    public function testMetaSearch() {
        $this->prepareDatabase();
        $em = $this->doctrine->getManager();

        $values = $em->getRepository(FileManagerObject::class)->searchByMeta('meta');
        $this->assertEquals(3, count($values));

        $values = $em->getRepository(FileManagerObject::class)->searchByMeta('meta2');
        $this->assertEquals(1, count($values));

        $values = $em->getRepository(FileManagerObject::class)->searchByMeta('metanol');
        $this->assertEquals(0, count($values));
    }
}