<?php

namespace CraftKeen\FCRBundle\Repository;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use Doctrine\ORM\EntityRepository;

class TranslatableEntityRepository extends EntityRepository
{

    /**
     * @param $object
     * @param Language $language
     *
     * @return null|object
     */
    public function findExistingTranslation($object, Language $language)
    {
        return $this->findOneBy(['langParent' => $object, 'lang' => $language]);
    }

    /**
     * @param $object
     *
     * @return array
     */
    public function findTranslations($object)
    {
        return $this->findBy(['langParent' => $object]);
    }
}
