<?php

namespace CraftKeen\FCRBundle\Import;

use CraftKeen\FCRBundle\Entity\AnalystCoverage;
use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;

class AnalystCoverageImport extends AbstractImport
{
    /**
     * PressReleaseImport constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * Clean up tables
     *
     * @return void
     */
    public function eraseTables()
    {
        $this->em->getRepository(AnalystCoverage::class)->truncate();
    }

    /**
     * Load Dependent Tables
     *
     * @return void
     */
    public function loadDependencies()
    {
        if (!isset($this->records['analyst_coverage'])) {
            throw new Exception('Dependencies was not loaded properly');
        }

        $response['analyst_coverage'] = $this->loadAnalystCoverage($this->records['analyst_coverage']);

        return $response;
    }


    /**
     * Load Historical Dividends
     *
     * @param array $items
     * @return void
     */
    private function loadAnalystCoverage($items)
    {
        $count = 0;
        foreach (['en', 'fr'] as $langKey) {
            $lang = $this->em->getRepository(Language::class)->findOneBy(['locale' => $langKey.'-ca']);
            foreach ($items as $key => $item) {
                if ( $item['lang'] == $langKey ) {
                    $analystCoverage = new AnalystCoverage();
                    $analystCoverage->setId($item['id']);
                    $analystCoverage->setTitle($item['title']);
                    $analystCoverage->setPerson($item['person']);
                    $analystCoverage->setPhone($item['phone']);
                    $analystCoverage->setType($item['type']);
                    $analystCoverage->setLang($lang);
                    $analystCoverage->setStatus('live');
                    if ( null !== $item['lang_parent'] ) {
                        $parent = $this->em->getRepository(AnalystCoverage::class)->findOneById( $item['lang_parent'] );
                        $analystCoverage->setLangParent($parent);
                    }
                    $analystCoverage->setCreatedBy($this->user);
                    $analystCoverage->setUpdatedBy($this->user);

                    $metadata = $this->em->getClassMetaData(get_class($analystCoverage));
                    $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                    $this->em->persist($analystCoverage);
                    $count++;
                }
            }
            $this->em->flush();
        }
		return $count;
    }
}
