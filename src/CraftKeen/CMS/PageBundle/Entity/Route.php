<?php

namespace CraftKeen\CMS\PageBundle\Entity;

use CraftKeen\Bundle\ComponentBundle\Model\SluggableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Route
 *
 * @ORM\Table(name="route")
 * @ORM\Entity(repositoryClass="CraftKeen\CMS\PageBundle\Repository\RouteRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @deprecated Should be renamed to RouteAlias
 */
class Route implements SluggableInterface
{
    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Page
     *
     * Many Routes have One Page.
     * @ORM\ManyToOne(
     *     targetEntity="\CraftKeen\CMS\PageBundle\Entity\Page",
     *     cascade={"persist"},
     *     inversedBy="routes",
     *     fetch="EXTRA_LAZY"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    protected $page;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     * @Gedmo\Versioned
     */
    protected $slug;

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
     * Set slug
     *
     * @param string $slug
     *
     * @return Route
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set page
     *
     * @param Page $page
     *
     * @return Route
     */
    public function setPage(Page $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }
}
