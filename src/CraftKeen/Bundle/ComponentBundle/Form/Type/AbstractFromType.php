<?php

namespace CraftKeen\Bundle\ComponentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class AbstractFromType extends AbstractType
{
    /** @var string */
    protected $dataClass;

    /**
     * @param string $dataClass
     */
    public function setDataClass($dataClass)
    {
        $this->dataClass = $dataClass;
    }
}
