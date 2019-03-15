<?php

namespace CraftKeen\CMS\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageWidget
 *
 * @ORM\Table(name="page_widget")
 * @ORM\Entity(repositoryClass="CraftKeen\CMS\PageBundle\Repository\PageWidgetRepository")
 */
class PageWidget
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
     * @var Page
     *
     * Many PageWidgets have One Page.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\PageBundle\Entity\Page", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $page;

    /**
     * @var Widget
     *
     * Many PageWidgets have One Page.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\PageBundle\Entity\Widget", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $widget;

    /**
     * @var string
     *
     * @ORM\Column(name="config", type="text", length=65535, nullable=true)
     */
    protected $config;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text", length=65535, nullable=false)
     */
    protected $data;

    /**
     * @var string
     *
     * @ORM\Column(name="data_type", type="string", length=100, nullable=false)
     */
    protected $dataType;

    /**
     * @var string
     *
     * @ORM\Column(name="tpl_area", type="string", length=100, nullable=false)
     */
    protected $tplArea;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=false)
     */
    protected $status;

    /**
     * @var integer
     *
     * One PageWidget have One Copy of PageWidget.
     * @ORM\OneToOne(targetEntity="PageWidget", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->dataType;
    }

    /**
     * {@inheritdoc}
     */
    public function __clone()
    {
        $this->id = null;
        $this->widget = clone $this->widget;
        $this->page = null;
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
     * Set config
     *
     * @param string $config
     *
     * @return PageWidget
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get config
     *
     * @return string
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set data
     *
     * @param string $data
     *
     * @return PageWidget
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set dataType
     *
     * @param string $dataType
     *
     * @return PageWidget
     */
    public function setDataType($dataType)
    {
        $this->dataType = $dataType;

        return $this;
    }

    /**
     * Get dataType
     *
     * @return string
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * Set tplArea
     *
     * @param string $tplArea
     *
     * @return PageWidget
     */
    public function setTplArea($tplArea)
    {
        $this->tplArea = $tplArea;

        return $this;
    }

    /**
     * Get tplArea
     *
     * @return string
     */
    public function getTplArea()
    {
        return $this->tplArea;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return PageWidget
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set page
     *
     * @param Page $page
     *
     * @return PageWidget
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

    /**
     * Set widget
     *
     * @param Widget $widget
     *
     * @return PageWidget
     */
    public function setWidget(Widget $widget)
    {
        $this->widget = $widget;

        return $this;
    }

    /**
     * Get widget
     *
     * @return Widget
     */
    public function getWidget()
    {
        return $this->widget;
    }

    /**
     * Set copyOf
     *
     * @param \CraftKeen\CMS\PageBundle\Entity\PageWidget $copyOf
     *
     * @return PageWidget
     */
    public function setCopyOf(\CraftKeen\CMS\PageBundle\Entity\PageWidget $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return \CraftKeen\CMS\PageBundle\Entity\PageWidget
     */
    public function getCopyOf()
    {
        return $this->copyOf;
    }
}
