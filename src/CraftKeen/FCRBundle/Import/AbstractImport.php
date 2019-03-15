<?php

namespace CraftKeen\FCRBundle\Import;

use CraftKeen\CMS\AdminBundle\Entity\Site;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Finder\Finder;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\UserBundle\Entity\User;
use Symfony\Component\Config\Definition\Exception\Exception;

abstract class AbstractImport
{
    const SORT_ORDER = 0;

    /** @var EntityManager */
    protected $em;

    /**
     * @var array
     */
    protected $records;

    /**
     * @var array
     */
    protected $lang;

    /**
     * @var User
     */
    protected $user;

    /** @var Site */
    protected $site;

    /**
     * @var array
     */
    private $csvParsingOptions = [
        'finder_in' => 'app/Resources/',
    ];

    /**
     * AbstractImport constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->lang['en'] = $this->em->getRepository(Language::class)->findOneBy(['locale' => 'en-ca']);
        $this->lang['fr'] = $this->em->getRepository(Language::class)->findOneBy(['locale' => 'fr-ca']);
        $this->user = $this->em->getRepository(User::class)->find(1);
        $this->site = $this->site = $this->em->getRepository(Site::class)->findOneById(1);
    }

    /**
     * Erases Tables Before Import
     *
     * @return mixed
     */
    abstract protected function eraseTables();

    /**
     * Load Dependencies, other tables
     *
     * @return mixed
     */
    abstract protected function loadDependencies();

    /**
     * Load and Convert CSV Files in Array of objects
     *
     * @param array $files
     */
    public function loadAndConverCSVFiles($files = [])
    {
        if (!is_array($files) && count($files)) {
            throw new Exception('CSV files must to an array and not empty!');
        }

        foreach ($files as $name => $file) {
            $this->records[$name] = $this->parseCSV($file);
        }
    }

    /**
     * Parse a CSV file
     *
     * @param $filePath
     * @param string $delimiter
     *
     * @return array
     */
    protected function parseCSV($filePath, $delimiter = ',')
    {
        $finder = new Finder();
        $finder->files()
            ->in($this->csvParsingOptions['finder_in'])
            ->name($filePath);
        $csv = null;
        foreach ($finder as $file) {
            $csv = $file;
        }

        $header = null;
        $data = [];
        if (($handle = fopen($csv->getRealPath(), "r")) !== false) {
            while (($row = fgetcsv($handle, null, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    if ("NULL" == $row) {
                        $row = null;
                    }
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        } else {
            die("Cannot Read file!!");
        }
        return $data;
    }

    /**
     * Get Records Array
     *
     * @return array
     */
    public function getRecords()
    {
        return $this->records;
    }

    public function setCsvParsingOptions($csvParsingOptions)
    {
        $this->csvParsingOptions = $csvParsingOptions;
    }

    protected function processField($field)
    {
        if ("NULL" == $field) {
            return null;
        }

        if (strlen($field) == 0) {
            return null;
        }

        return $field;
    }

    /**
     * Transform (e.g. "Hello World") into a slug (e.g. "hello-world").
     *
     * @param string $string
     *
     * @return string
     */
    protected function slugify($string)
    {
        $rule = 'NFD; [:Nonspacing Mark:] Remove; NFC';
        $transliterator = \Transliterator::create($rule);
        $string = $transliterator->transliterate($string);

        $niceFilters = str_replace(
            ['%'],
            ['percent'],
            strtolower(trim(strip_tags($string)))
        );

        return str_replace('--', '', preg_replace(
            '/[^a-z0-9]/',
            '-',
            strtolower(trim(strip_tags($niceFilters)))
        ));
    }
}
