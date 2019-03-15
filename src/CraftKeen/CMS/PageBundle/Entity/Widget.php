<?php

namespace CraftKeen\CMS\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Widget
 *
 * @ORM\Table(name="widget")
 * @ORM\Entity(repositoryClass="CraftKeen\CMS\PageBundle\Repository\WidgetRepository")
 */
class Widget
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=250, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100, nullable=false)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="macros", type="string", length=250, nullable=false)
     */
    protected $macros;

    /**
     * @var string
     *
     * @ORM\Column(name="default_data", type="text", length=65535, nullable=true)
     */
    protected $defaultData;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    function __clone()
    {
        $this->id = null;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Widget
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Widget
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set macros
     *
     * @param string $macros
     *
     * @return Widget
     */
    public function setMacros($macros)
    {
        $this->macros = $macros;

        return $this;
    }

    /**
     * Get macros
     *
     * @return string
     */
    public function getMacros()
    {
        return $this->macros;
    }

    /**
     * Set data
     *
     * @param string $defaultData
     *
     * @return Widget
     */
    public function setDefaultData($defaultData)
    {
        $this->defaultData = $defaultData;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getDefaultData()
    {
        return $this->defaultData;
    }
}
