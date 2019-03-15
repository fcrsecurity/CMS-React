<?php

namespace CraftKeen\BrochureBuilderBundle\Twig;

class StringHelpersExtension extends \Twig_Extension
{
    private $messages = [
        'en' => [
            'tagline' => 'Shopping for everyday life',
            'population' => 'Population',
            'demographics_2017' => '2017 Demographics',
            'avg_household_income' => 'Avg. Household Income',
            'total_households' => 'Total Households',
            'traffic_count' => 'Traffic Count',
            'aadt' => 'AADT',
        ],
        'fr' => [
            'tagline' => 'Vos achats au quotidien',
            'population' => 'Population',
            'demographics_2017' => 'Démographiques de 2017',
            'avg_household_income' => 'Revenu moyen du ménage',
            'total_households' => 'Total des ménages',
            'traffic_count' => 'Recensement de la circulation',
            'aadt' => 'TMJA',
        ]
    ];

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('toLocaleString', [$this, 'toLocaleString']),
            new \Twig_SimpleFunction('translateBB', [$this, 'translateBB'])
        ];
    }

    /**
     * Format float value locale string
     *
     * @param float $value
     * @return string
     * @throws \Twig_Error_Runtime
     */
    public function toLocaleString($value, $prefix = '')
    {
        return $prefix.number_format(intval($value, 10));
    }

    /**
     * Simple translate helper
     *
     * @param string $key
     * @param string $lang = 'en'
     * @return string
     * @throws \Twig_Error_Runtime
     */
    public function translateBB($key, $lang = 'en')
    {
        $lang = strtolower($lang);
        $lang = 'fr' === $lang || 'fr_ca' === $lang || 'fr-ca' === $lang ? 'fr' : 'en';
        return $key && isset($this->messages[$lang][$key]) ? $this->messages[$lang][$key] : $key;
    }
}
