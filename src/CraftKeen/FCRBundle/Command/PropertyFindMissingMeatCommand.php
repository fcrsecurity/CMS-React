<?php

namespace CraftKeen\FCRBundle\Command;

use CraftKeen\FCRBundle\Entity\Property;
use CraftKeen\FCRBundle\Entity\PropertyDemographic;
use CraftKeen\FCRBundle\Entity\PropertyDetails;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PropertyFindMissingMeatCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:property:find-missing-meta')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Finding Properties with Missing Meta');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $properties = $em->getRepository(Property::class)->findAll();

        $missingMeta = [
            'details' => [],
            'demographics' => [],
        ];
        /** @var Property $property */
        foreach ( $properties as $property ) {
            // Fix Missing Details issue.
            $details = $em->getRepository(PropertyDetails::class)->findBy(['property' => $property]);
            if (null == $details) {
                // Apply fix
                if (null !== $property->getCopyOf()) {
                    /** @var PropertyDetails $copyOfDetails */
                    $copyOfDetails = $details = $em->getRepository(PropertyDetails::class)
                        ->findBy(['property' => $property->getCopyOf()]);
                    if (null !== $copyOfDetails) {
                        $property->setDetails($property->getDetails()->copyDataFrom($copyOfDetails));

                        dump($property->getDetails());
                        die;
                    }
                } else {
                    // Try to find and repair Details from Parent Translation.
                    if (
                        $property->getLang()->getCode() == 'fr_CA' &&
                        null !== $property->getLangParent() &&
                        null !== $property->getLangParent()->getDetails()
                    ) {

                        $newDetails = new PropertyDetails();
                        $newDetails->setProperty($property);
                        $property->setDetails($newDetails);
                        //$em->flush($property);
                        $missingMeta['details-fixed'][$property->getId()] = $property->getId();
                    } else {
                        //echo "ISSUE! This property MUST has Details, or to be removed\n";
                        $missingMeta['details-critical'][$property->getId()] = $property;
                    }
                }
            }

            // FIX Missing Demographics Issues
            $demographics = $em->getRepository(PropertyDemographic::class)
                ->findBy(['property' => $property]);
            if (null == $demographics) {
                // Apply fix
                if (null !== $property->getCopyOf()) {
                    /** @var PropertyDemographic $copyOfDemographic */
                    $copyOfDemographic = $em->getRepository(PropertyDemographic::class)->findBy(['property'
                    => $property->getCopyOf()]);
                    dump($copyOfDemographic);
                    die('HERE');

                } else {
                    // Try to find and get Demographics from Parent Translation.
                    if (
                        $property->getLang()->getCode() == 'fr_CA' &&
                        null !== $property->getLangParent() &&
                        null !== $property->getLangParent()->getDemographic()
                        ) {

                        $newDemographics = new PropertyDemographic();
                        $newDemographics->setProperty($property);
                        $property->setDemographic($newDemographics);
                        $em->flush($property);
                        $missingMeta['demographics-fixed'][$property->getId()] = $property->getId();
                    } else {
                        //echo "ISSUE! This property MUST has Demographic, or to be removed\n";
                        $missingMeta['demographics-critical'][$property->getId()] = $property->getId();
                    }
                }
            }
        }

        $details = $em->getRepository(PropertyDetails::class)->findAll();
        /** @var PropertyDetails $detail */
        foreach ($details as $detail) {
            if (null == $detail->getProperty()) {
                $missingMeta['details-missing-properties'][$detail->getId()] = $detail->getId();
            }
        }

        $demographics = $em->getRepository(PropertyDemographic::class)->findAll();
        /** @var PropertyDemographic $demographic */
        foreach ($demographics as $demographic) {
            if (null == $demographic->getProperty()) {
                $missingMeta['demographic-missing-properties'][$demographic->getId()] = $demographic->getId();
            }
        }

        dump($missingMeta);
    }
}
