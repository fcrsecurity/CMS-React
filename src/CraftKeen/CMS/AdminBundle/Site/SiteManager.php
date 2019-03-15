<?php

namespace CraftKeen\CMS\AdminBundle\Site;

use CraftKeen\CMS\AdminBundle\Entity\Site;

class SiteManager
{
    /**
     * @var Site
     */
    private $currentSite;

    /**
     * @return Site
     */
    public function getCurrentSite()
    {
        return $this->currentSite;
    }

    /**
     * @param Site $currentSite
     */
    public function setCurrentSite(Site $currentSite)
    {
        $this->currentSite = $currentSite;
    }
}
