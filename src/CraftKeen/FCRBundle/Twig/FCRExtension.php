<?php

namespace CraftKeen\FCRBundle\Twig;

use CraftKeen\FCRBundle\Entity\PropertyDemographic;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class FCRExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('phone_number_format', [$this, 'phoneNumberFormatFilter']),
            new \Twig_SimpleFilter('validateDemographics', [$this, 'validateDemographicsFilter']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            //new \Twig_SimpleFunction('orderBy', [$this, 'orderByFunction']),
        ];
    }

    /**
     * Perform Phone number filter
     *
     * Default Canadian Phone Number format: xxx-xxx-xxxx,
     * but there maybe characters before number like +1 800 ... we still want to display it.
     * It uses 4 match detection, most important to have xxx-xxx-xxxx at the end of the number.
     *
     * @param string $phoneNumber
     * @return string mixed
     */
    public function phoneNumberFormatFilter($phoneNumber)
    {
        $normalizedString = str_replace(['-',' '],'', trim($phoneNumber));
        if (preg_match( '/^(.*)(\d{3})(\d{3})(\d{4})$/', $normalizedString,  $matches ) )
        {
            if (isset($matches[4])) {
                $result = $matches[2] . '-' .$matches[3] . '-' . $matches[4];
                if (strlen($matches[1]) > 0) {
                    $result = $matches[1] . ' ' .$matches[2] . '-' .$matches[3] . '-' . $matches[4];
                }
                return $result;
            }
        }

        return $phoneNumber;
    }

    public function validateDemographicsFilter(PropertyDemographic $propertyDemographic)
    {
        if (
            null !== $propertyDemographic->getPopulation1km() && $propertyDemographic->getPopulation1km() > 0 &&
            null !== $propertyDemographic->getPopulation3km() && $propertyDemographic->getPopulation3km() > 0 &&
            null !== $propertyDemographic->getPopulation5km() && $propertyDemographic->getPopulation5km() > 0 &&
            null !== $propertyDemographic->getHousehold1km() && $propertyDemographic->getHousehold1km() > 0 &&
            null !== $propertyDemographic->getHousehold3km() && $propertyDemographic->getHousehold3km() > 0 &&
            null !== $propertyDemographic->getHousehold5km() && $propertyDemographic->getHousehold5km() > 0 &&
            null !== $propertyDemographic->getHouseholdIncome1km() && $propertyDemographic->getHouseholdIncome1km() > 0 &&
            null !== $propertyDemographic->getHouseholdIncome3km() && $propertyDemographic->getHouseholdIncome3km() > 0 &&
            null !== $propertyDemographic->getHouseholdIncome5km() && $propertyDemographic->getHouseholdIncome5km() > 0

        ) {
            return true;
        }

        return false;
    }
}
