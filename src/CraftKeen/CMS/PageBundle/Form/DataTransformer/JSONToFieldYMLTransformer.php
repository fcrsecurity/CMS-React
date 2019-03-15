<?php

namespace CraftKeen\CMS\PageBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Yaml\Yaml;

class JSONToFieldYMLTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  string $jsonStr
     * @return string
     */
    public function transform($jsonStr)
    {
        if (null === $jsonStr) {
            return '';
        }

        return Yaml::dump((json_decode($jsonStr, true)), 10);
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $ymlString
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($ymlString)
    {
        $array = Yaml::parse($ymlString);      
        return json_encode($array);
    }
}