<?php

namespace CraftKeen\FCRBundle\Command;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\FCRBundle\Entity\CareersPosition;

class PullCareersPositionCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:careers:pull-positions')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Getting Careers Positions');
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $connector = $this->getContainer()->get('craft_keen_fcr.service.catsone_connector');
        // $logger = $this->getContainer()->get('logger');
        
        $connector->setApiKey($this->getContainer()->getParameter('fcr_catsone_api_key'));
        $jobs = $connector->getPositions();

        $counter = 0;
        
        if (null !== $jobs) {
            
            $em->getRepository(CareersPosition::class)->truncate();
            
            foreach ($jobs->_embedded->jobs as $key => $value) {
                if (4545330 == $value->status_id) { // 4545330 =  active status on position
                    $counter++;
                    $careersPosition = new CareersPosition();
                    $careersPosition->setCode($value->id);
                    $careersPosition->setCategoryName($value->category_name);
                    $careersPosition->setCity($value->location->city);
                    $careersPosition->setState($value->location->state);
                    $careersPosition->setTitle($value->title);
                    $careersPosition->setDescription($value->description);
                    $careersPosition->setSite( $em->getRepository(Site::class)->find(1) );
                    $careersPosition->setLang( $em->getRepository(Language::class)->find(1) );

                    $em->persist($careersPosition);
                }
            }
            $em->flush();

            $message = "Done. Added $counter positions";
        } else {
            $message = "Nothing was found";
        }
        
        $output->writeln(['Message: '.$message, '============']);
    }
}
