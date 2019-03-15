<?php

namespace CraftKeen\Bundle\WidgetBundle\Model;

use CraftKeen\CMS\PageBundle\Entity\PageWidget;

interface WidgetInterface
{
    /**
     * @param PageWidget $source
     */
    public function setSource(PageWidget $source);

    /**
     * Returns unique widget identifier
     *
     * @return string
     */
    public function getUid();

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * Returns data required to properly render template
     *
     * @return array
     */
    public function getTemplateData();

    /**
     * Returns 'true' if widgets supports caching
     *
     * @return bool
     */
    public function supportsCache();

    /**
     * Returns 'true' if model can handle PageWidget object
     *
     * @param PageWidget $source
     *
     * @return bool
     */
    public function isApplicable(PageWidget $source);
}
