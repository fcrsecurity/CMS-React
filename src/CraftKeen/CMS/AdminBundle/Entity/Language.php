<?php

namespace CraftKeen\CMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Intl\Intl;

/**
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="CraftKeen\CMS\AdminBundle\Repository\LanguageRepository")
 *
 * TODO: Should be moved to CraftKeenTranslationBundle
 */
class Language
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=10, unique=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=10, unique=true)
     */
    protected $locale;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @param null $locale
     *
     * @return string
     */
    public function getName($locale = null)
    {
        // TODO: Find a Better way of showing correct locale.
        return strstr($this->getCode(), "_", true);

        //$name = Intl::getLocaleBundle()->getLocaleName($this->getCode(), $locale ?: $this->getCode());
        //return $name ?: $this->getCode();
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Language
     */
    public function setCode($code)
    {
        $this->code = $code;
        $this->setLocale($code);

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return Language
     */
    protected function setLocale($locale)
    {
        $this->locale = strtolower(str_replace('_', '-', $locale));

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
