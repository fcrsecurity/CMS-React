<?php

namespace CraftKeen\Bundle\WidgetBundle\Model;

use CraftKeen\CMS\PageBundle\Entity\PageWidget;

abstract class AbstractWidget implements WidgetInterface
{
    /** @var PageWidget */
    protected $source;

    /** @var string */
    protected $template;

    /** @var array */
    protected $templateData = [];

    /**
     * {@inheritdoc}
     */
    public function setSource(PageWidget $source)
    {
        $this->source = $source;
    }

    /**
     * {@inheritdoc}
     */
    public function getUid()
    {
        return sprintf(
            '%s-%s-%s',
            $this->source->getDataType(),
            $this->source->getId(),
            $this->source->getPage()->getId()
        );
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return array
     */
    public function getTemplateData()
    {
        return [
            'fields' => $this->getFields(),
            'widget' => [
                'id' => $this->source->getId(),
                'wid' => $this->source->getWidget()->getId(),
                'name' => $this->source->getWidget()->getName(),
                'macros' => $this->source->getWidget()->getMacros(),
                'tplArea' => $this->source->getTplArea(),
                'data' => unserialize($this->source->getData()),
                'dataType' => $this->source->getDataType(),
                'config' => unserialize($this->source->getConfig()),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsCache()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isApplicable(PageWidget $source)
    {
        $calledClass = explode('\\', get_called_class());
        $class = str_replace('Widget', '', end($calledClass));

        return strtolower($source->getDataType()) == strtolower($class);
    }

    /**
     * @return array
     */
    protected function getFields()
    {
        return ['text'];
    }
}
