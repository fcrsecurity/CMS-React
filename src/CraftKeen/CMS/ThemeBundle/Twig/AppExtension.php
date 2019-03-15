<?php

namespace CraftKeen\CMS\ThemeBundle\Twig;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\PageBundle\Entity\Page;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use CraftKeen\FCRBundle\Entity;

class AppExtension extends \Twig_Extension implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('remove_inline_styles', array($this, 'removeInlineStyles')),
            new \Twig_SimpleFilter('script_list', array($this, 'getScripts')),
            new \Twig_SimpleFilter('financial_report_latest_url', array($this, 'financialReportLatestUrl')),
            new \Twig_SimpleFilter('financial_report_latest_title', array($this, 'financialReportLatestTitle')),
        );
    }

    /**
     * @return array
     *
     * TODO: move this functions in to FCR bundle
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('available_locales', [$this, 'availableLocales']),
            new \Twig_SimpleFunction('leasing_provinces', [$this, 'getLeasingProvinces']),
            new \Twig_SimpleFunction('leasing_cities', [$this, 'getLeasingCities']),
            new \Twig_SimpleFunction('leasing_properties', [$this, 'getLeasingProperties']),
            new \Twig_SimpleFunction(
                'leasing_properties_featured_slider',
                [$this, 'getLeasingPropertiesFeaturesSlider']
            ),
            new \Twig_SimpleFunction('investors_financial_reports', [$this, 'getInvestorsFinancialReports']),
            new \Twig_SimpleFunction('investors_press_releases', [$this, 'getInvestorsPressReleases']),
            new \Twig_SimpleFunction('investors_press_releases_latest', [$this, 'getInvestorsPressReleasesLatest']),
            new \Twig_SimpleFunction('investors_dividends', [$this, 'getInvestorsDividends']),
            new \Twig_SimpleFunction('investors_debenture', [$this, 'getInvestorsDebentures']),
            new \Twig_SimpleFunction('retail_art_posts', [$this, 'getRetailArtPosts']),
            new \Twig_SimpleFunction('sustainability_reports', [$this, 'getSustainabilityReport']),
            new \Twig_SimpleFunction('sustainability_slider', [$this, 'getSustainabilitySlider']),
            new \Twig_SimpleFunction('faq', [$this, 'getFaq']),
            new \Twig_SimpleFunction('careers_slider', [$this, 'getCareersSlider']),
            new \Twig_SimpleFunction('careers_employee', [$this, 'getCareersEmployee']),
            new \Twig_SimpleFunction('careers_positions', [$this, 'getCareersPositions']),
            new \Twig_SimpleFunction('people_about', [$this, 'getPeopleAbout']),
            new \Twig_SimpleFunction('investors_conference_calls', [$this, 'getInvestorsConferenceCalls']),
            new \Twig_SimpleFunction('investors_ananlyst_coverage', [$this, 'getInvestorsAnalystCoverage']),
            new \Twig_SimpleFunction('contact_offices', [$this, 'getContactOffices']),
            new \Twig_SimpleFunction('checkWidgetFields', [$this, 'checkWidgetFields']),
        ];
    }

    /**
     * Removed All Inline styles. Used on Jobs Details Page for custom CatsOne styles removal
     *
     * @param string $html
     *
     * @return string
     */
    public function removeInlineStyles($html)
    {
        $html = preg_replace('#(<[a-z ]*)(style=("|\')(.*?)("|\'))([a-z ]*>)#', '\\1\\6', $html);

        return $html;
    }

    /**
     * @param $scriptString
     * @param $assetPath
     *
     * @return array|string[]
     */
    public function getScripts($scriptString, $assetPath)
    {
        $scripts = [];
        foreach (explode(',', $scriptString) as $item) {
            $file = $this->container->getParameter('web_path') . DIRECTORY_SEPARATOR . $assetPath . $scriptString;
            if (file_exists($file)) {
                $scripts[] = $item;
            }
        }

        return $scripts;
    }

    /**
     * @param Entity\FinancialReport|mixed $object
     *
     * @return string
     */
    public function financialReportLatestUrl($object)
    {
        if ($object instanceof Entity\FinancialReport) {
            if (null !== $object->getAnnual()) {
                return $object->getAnnual();
            }
            if (null !== $object->getQ4()) {
                return $object->getQ4();
            }
            if (null !== $object->getQ3()) {
                return $object->getQ3();
            }
            if (null !== $object->getQ2()) {
                return $object->getQ2();
            }
            if (null !== $object->getQ1()) {
                return $object->getQ1();
            }
        }

        return '';
    }

    /**
     * @param Entity\FinancialReport|mixed $object
     *
     * @return string
     */
    public function financialReportLatestTitle($object)
    {
        if ($object instanceof Entity\FinancialReport) {
            if (null !== $object->getAnnual()) {
                return 'Annual';
            }
            if (null !== $object->getQ4()) {
                return 'Q4';
            }
            if (null !== $object->getQ3()) {
                return 'Q3';
            }
            if (null !== $object->getQ2()) {
                return 'Q2';
            }
            if (null !== $object->getQ1()) {
                return 'Q1';
            }
        }

        return '';
    }

    /**
     * Provides a list of available locales for switcher
     *
     * @param string $locale
     *
     * @return Language[]
     */
    public function availableLocales($locale)
    {
        $languages = $this->getRepository(Language::class)->findAllBut($locale);

        return $languages;
    }

    /**
     * Get Leasing Provinces
     *
     * @return array
     */
    public function getLeasingProvinces()
    {
        return $this->getRepository(Entity\ActiveProvince::class)->findProvincesMap(
            $this->getCurrentLanguage()
        );
    }

    /**
     * Get Leasing Provinces
     *
     * @return mixed
     */
    public function getLeasingCities()
    {
        return $this->getRepository(Entity\Property::class)->findCitiesMap($this->getCurrentLanguage());
    }

    /**
     * Get Leasing Provinces
     *
     * @param string $province
     * @param int $offset
     * @param int $limit
     * @param null $filter
     * @param bool $nolimit
     * @param bool $random
     *
     * @return mixed
     */
    public function getLeasingProperties(
        $province = 'AB',
        $offset = 0,
        $limit = 12,
        $filter = null,
        $nolimit = false,
        $random = false
    ) {
        return $this->getRepository(Entity\Property::class)
            ->findPropertiesListing(
                $this->getCurrentLanguage(),
                $province,
                $offset,
                $limit,
                $filter,
                $nolimit,
                $random
            );
    }

    /**
     * Get Property Feature Slider per Page.
     *
     * @param Page $page
     *
     * @return Entity\PropertyFeatureSlider[]|array
     */
    public function getLeasingPropertiesFeaturesSlider(Page $page)
    {
        return $this->getRepository(Entity\PropertyFeatureSlider::class)
            ->findBy(
                [
                    'lang' => $this->getCurrentLanguage(),
                    'page' => $page,
                    'status' => 'live',
                ],
                [
                    'sortOrder' => 'ASC',
                ]
            );
    }

    /**
     * Get Financial Reports
     *
     * @param boolean $latest Only Latest
     *
     * @return Entity\FinancialReport|Entity\FinancialReport[]|array
     */
    public function getInvestorsFinancialReports($latest = false)
    {
        if ($latest) {
            return $this->getRepository(Entity\FinancialReport::class)
                ->findOneBy(
                    [
                        'lang' => $this->getCurrentLanguage(),
                        'status' => 'live',
                    ],
                    [
                        'year' => 'DESC',
                    ]
                );
        }

        return $this->getRepository(Entity\FinancialReport::class)->findBy([
            'lang' => $this->getCurrentLanguage(),
            'status' => 'live',
        ]);
    }

    /**
     * Get List of FAQ
     *
     * @param integer $category FAQ Category
     *
     * @return Entity\FAQ[]|array
     */
    public function getFaq($category = 1)
    {
        return $this->getRepository(Entity\FAQ::class)
            ->findBy(
                [
                    'category' => $category,
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                ],
                [
                    'sortOrder' => 'ASC',
                ]
            );
    }

    /**
     * Get Dividends
     *
     * @return Entity\Dividend[]|array
     */
    public function getInvestorsDividends()
    {
        $items = $this->getRepository(Entity\Dividend::class)->findBy(['status' => 'live'], ['recordDate' => 'DESC']);

        $dividends = [];
        foreach ($items as $d) {
            $date = $d->getDeclaredDate();
            $dividends[$date->format('Y')][] = $d;
        }
        krsort($dividends);

        return $dividends;
    }

    /**
     * Get Debentures
     *
     * @return Entity\Debenture[]|array
     */
    public function getInvestorsDebentures()
    {
        $items = $this->getRepository(Entity\Debenture::class)->findBy(['status' => 'live'], ['maturityDate' => 'ASC']);

        $debentures = [];
        foreach ($items as $d) {
            $series = $d->getSeries();
            $debentures[$series] = $d;
        }

        return $debentures;
    }

    /**
     * Get Sustainability Reports
     *
     * @param boolean $latest Only Latest
     *
     * @return Entity\Sustainability|Entity\Sustainability[]
     */
    public function getSustainabilityReport($latest = false)
    {
        if ($latest) {
            return $this->getRepository(Entity\Sustainability::class)
                ->findOneBy(
                    [
                        'lang' => $this->getCurrentLanguage(),
                        'status' => 'live',
                    ],
                    [
                        'year' => 'DESC',
                    ]
                );
        }

        return $this->getRepository(Entity\Sustainability::class)
            ->findBy(
                [
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                ],
                [
                    'year' => 'DESC',
                ]
            );
    }

    /**
     * @return Entity\SustainabilitySlider[]|array
     */
    public function getSustainabilitySlider()
    {
        return $this->getRepository(Entity\SustainabilitySlider::class)
            ->findBy(
                [
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                ],
                [
                    'sortOrder' => 'ASC',
                ]
            );
    }

    /**
     * Get Slider on Careers Page
     *
     * @return Entity\CareersSlider[]|array
     */
    public function getCareersSlider()
    {
        return $this->getRepository(Entity\CareersSlider::class)
            ->findBy(
                [
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                ]
            );
    }

    /**
     * Get Office on Contact Page
     *
     * @param bool $isMain
     * @param bool $singular
     *
     * @return Entity\Office|Entity\Office[]|array
     */
    public function getContactOffices($isMain = false, $singular = false)
    {
        if ($singular) {
            return $this->getRepository(Entity\Office::class)
                ->findOneBy(
                    [
                        'lang' => $this->getCurrentLanguage(),
                        'status' => 'live',
                        'isMain' => $isMain,
                    ]
                );
        }

        return $this->getRepository(Entity\Office::class)
            ->findBy(
                [
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                    'isMain' => $isMain,
                ],
                [
                    'sortOrder' => 'ASC',
                ]
            );
    }

    /**
     * Get Slider on Careers Page
     *
     * @return array
     */
    public function getInvestorsAnalystCoverage()
    {
        $items = $this->getRepository(Entity\AnalystCoverage::class)
            ->findBy(
                [
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                ]
            );

        $types = [];
        foreach ($items as $item) {
            if (!in_array($item->getType(), $types)) {
                $types[] = $item->getType();
            }
        }

        return [
            'types' => $types,
            'items' => $items,
        ];
    }

    /**
     * Get Positions
     *
     * @param string $province
     *
     * @return Entity\CareersPosition[]|array
     */
    public function getCareersPositions($province = 'ab')
    {
        return $this->getRepository(Entity\CareersPosition::class)->findBy(['state' => $province]);
    }

    /**
     * Get Employees
     *
     * @return Entity\CareersEmployee[]|array
     */
    public function getCareersEmployee()
    {
        return $this->getRepository(Entity\CareersEmployee::class)
            ->findBy(
                [
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                ],
                [
                    'sortOrder' => 'ASC',
                ]
            );
    }

    /**
     * Get People Listing filtered by category
     *
     * @param integer $category
     *
     * @return Entity\People[]|array
     */
    public function getPeopleAbout($category)
    {
        return $this->getRepository(Entity\People::class)
            ->findBy(
                [
                    'category' => $category,
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                ],
                [
                    'sortOrder' => 'ASC',
                ]
            );
    }

    /**
     * Get Investors Conference Calls filtered by category
     *
     * @param integer $category
     *
     * @return Entity\ConferenceCall[]|array
     */
    public function getInvestorsConferenceCalls($category)
    {
        return $this->getRepository(Entity\ConferenceCall::class)
            ->findBy(
                [
                    'category' => $category,
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                ],
                [
                    'date' => 'DESC',
                ],
                2 // Only 2 latest per category
            );
    }

    /**
     * Get Retails & Art Posts
     *
     * @return Entity\RetailArt[]|array
     */
    public function getRetailArtPosts()
    {
        return $this->getRepository(Entity\RetailArt::class)
            ->findBy(
                [
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                ]
            );
    }

    /**
     * Get List of Press Releases
     *
     * @param null|int $limit
     * @param int $offset
     *
     * @return Entity\PressRelease[]|array
     */
    public function getInvestorsPressReleases($limit = null, $offset = 0)
    {
        $articles = [];
        $findBy = [
            'lang' => $this->getCurrentLanguage(),
            'status' => 'live',
        ];

        $pressRelease = $this->getRepository(Entity\PressRelease::class)
            ->findBy($findBy, ['date' => 'DESC'], $limit, $offset);

        foreach ($pressRelease as $item) {
            $year = $item->getDate()->format('Y');
            $articles[$year][] = $item;
        }

        krsort($articles, SORT_NUMERIC);

        return $articles;
    }

    /**
     * Get List of Press Releases
     *
     * @param null|int $limit
     *
     * @return Entity\PressRelease[]|array
     */
    public function getInvestorsPressReleasesLatest($limit = null)
    {
        $articles = [];
        $findBy = [
            'lang' => $this->getCurrentLanguage(),
            'status' => 'live',
        ];

        $pressRelease = $this->getRepository(Entity\PressRelease::class)
            ->findBy($findBy, ['date' => 'DESC'], $limit);

        foreach ($pressRelease as $item) {
            $articles[] = [
                'date' => $item->getDate()->format('M d, Y'),
                'description' => $item->getTitle(),
                'href' => '/investors/press-release/' . $item->getSlug(),
            ];
        }

        return $articles;
    }

    /**
     * Check Widget Fields
     *
     * @param $data
     * @param $fields
     *
     * @return array
     */
    public function checkWidgetFields($data, $fields)
    {
        if (is_array($fields) && !is_array($data)) {
            $data = [];
        }

        foreach ($fields as $field) {
            if (!isset($data[$field]) && is_array($data)) {
                $data[$field] = '';
            }
        }

        return $data;
    }

    /**
     * @param string $class
     *
     * @return mixed|ObjectRepository|EntityRepository
     *
     */
    private function getRepository($class)
    {
        return $this->container->get('doctrine')->getManagerForClass($class)->getRepository($class);
    }

    /**
     * @return Language|null
     */
    private function getCurrentLanguage()
    {
        return $this->container->get('craft_keen.translation.provider.language')->getCurrentLanguage();
    }
}
