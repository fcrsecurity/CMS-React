<?php

namespace CraftKeen\CMS\PageBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Yaml\Yaml;

class SerializedToFieldYMLTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  string $serializedStr
     * @return string
     */
    public function transform($serializedStr)
    {
        if (null === $serializedStr) {
            return '';
        }

        return Yaml::dump((unserialize($serializedStr)), 2);
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
        return serialize($array);
    }
}