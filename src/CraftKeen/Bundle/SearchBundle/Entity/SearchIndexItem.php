<?php

namespace CraftKeen\Bundle\SearchBundle\Entity;

use CraftKeen\Bundle\SearchBundle\Model\SearchItem;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Site;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="search_index")
 * @ORM\Entity(repositoryClass="CraftKeen\Bundle\SearchBundle\Entity\Repository\SearchIndexItemRepository")
 */
class SearchIndexItem extends SearchItem
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
     * @ORM\Column(name="object_class", type="text", nullable=false)
     */
    protected $objectClass;

    /**
     * @var int
     *
     * @ORM\Column(name="object_id", type="integer", nullable=false)
     */
    protected $objectId;

    /**
     * @var Site
     *
     * Many Menus have One Language.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\AdminBundle\Entity\Site")
     * @ORM\JoinColumn(name="site_id", nullable=false, referencedColumnName="id")
     */
    protected $site;

    /**
     * @var Language
     *
     * Many Menus have One Language.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\AdminBundle\Entity\Language")
     * @ORM\JoinColumn(name="language_id", nullable=false, referencedColumnName="id")
     */
    protected $language;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer", nullable=false, options={"default"="0"})
     */
    protected $weight = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="short_body", type="text", nullable=false)
     */
    protected $shortBody;

    /**
     * @var string
     *
     * @ORM\Column(name="hidden_meta", type="text", nullable=false)
     */
    protected $hiddenMeta;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
