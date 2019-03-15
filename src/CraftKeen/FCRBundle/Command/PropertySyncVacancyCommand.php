<?php

namespace CraftKeen\FCRBundle\Command;

use CraftKeen\FCRBundle\Entity\Property;
use CraftKeen\FCRBundle\Entity\PropertyVacancy;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PropertySyncVacancyCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:property:sync-vacancy')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Finding Properties with Missing Meta');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $propertyVacancies = $em->getRepository(PropertyVacancy::class)->findAll();

        /** @var PropertyVacancy $vacancy */
        foreach ( $propertyVacancies as $vacancy ) {
           if( 'en_CA' == $vacancy->getProperty()->getLang()->getCode()) {
               /** @var Property $translation */
               $translation = $em->getRepository(Property::class)->findOneByLangParent($vacancy->getProperty());
               $tvac = $em->getRepository(PropertyVacancy::class)->findBy(['property' => $translation]);
               if (null == $tvac) {
                   /** @var PropertyVacancy $parentVacancy */
                   foreach ($vacancy->getProperty()->getVacancyList() as $parentVacancy) {
                       // Forced abort saving vacancies with empty property
                       if (null !== $translation) {
                           $newVacancy = new PropertyVacancy();
                           $newVacancy->setVacantSqft($parentVacancy->getVacantSqft());
                           $newVacancy->setProperty($translation);
                           $em->persist($newVacancy);
                       }
                   }
               }
           }
        }
        $em->flush();
    }
}
