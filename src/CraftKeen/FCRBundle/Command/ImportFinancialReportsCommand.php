<?php

namespace CraftKeen\FCRBundle\Command;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\FCRBundle\Entity\FinancialReport;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\UserBundle\Entity\User;

class ImportFinancialReportsCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:import:financial-reports')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $en = $em->getRepository(Language::class)->findOneBy(['locale' => 'en-ca']);
        $fr = $em->getRepository(Language::class)->findOneBy(['locale' => 'fr-ca']);
        $createdBy = $em->getRepository(User::class)->findOneById(1);

        for( $i=2007; $i <= 2017; $i++ ) {
            $financialReport = new FinancialReport();
            $financialReport->setLang($en);
            $financialReport->setYear($i);
            $financialReport->setQ1("https://fcr.ca/uploads/investors/financial-reports/pdf/$i/Q1.pdf");
            $financialReport->setQ2("https://fcr.ca/uploads/investors/financial-reports/pdf/$i/Q2.pdf");
            $financialReport->setQ3("https://fcr.ca/uploads/investors/financial-reports/pdf/$i/Q3.pdf");
            $financialReport->setQ4("https://fcr.ca/uploads/investors/financial-reports/pdf/$i/Q4.pdf");
            $financialReport->setAnnual("https://fcr.ca/uploads/investors/financial-reports/pdf/$i/AR.pdf");
            $financialReport->setStatus('live');
            $financialReport->setCreatedBy($createdBy);
            $em->persist($financialReport);

            $financialReportFr = clone $financialReport;
            $financialReportFr->setLang($fr);
            $financialReportFr->setLangParent($financialReport);
            $financialReportFr->setQ1("https://fcr.ca/uploads/investors/financial-reports/pdf/$i/Q1_fr.pdf");
            $financialReportFr->setQ2("https://fcr.ca/uploads/investors/financial-reports/pdf/$i/Q2_fr.pdf");
            $financialReportFr->setQ3("https://fcr.ca/uploads/investors/financial-reports/pdf/$i/Q3_fr.pdf");
            $financialReportFr->setQ4("https://fcr.ca/uploads/investors/financial-reports/pdf/$i/Q4_fr.pdf");
            $financialReportFr->setAnnual("https://fcr.ca/uploads/investors/financial-reports/pdf/$i/AR_fr.pdf");
            $financialReportFr->setStatus('live');
            $financialReportFr->setCreatedBy($createdBy);
            $em->persist($financialReportFr);

        }
        $em->flush();

    }
}
