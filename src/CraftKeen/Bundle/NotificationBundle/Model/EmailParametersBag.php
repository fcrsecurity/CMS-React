<?php

namespace CraftKeen\Bundle\NotificationBundle\Model;

class EmailParametersBag
{
    /** @var string */
    protected $fcrPropertyCtaEmailFrom;

    /** @var string */
    protected $fcrPropertyCtaEmailLabel;

    /**
     * @return string
     */
    public function getFcrPropertyCtaEmailFrom()
    {
        return $this->fcrPropertyCtaEmailFrom;
    }

    /**
     * @param string $fcrPropertyCtaEmailFrom
     *
     * @return $this
     */
    public function setFcrPropertyCtaEmailFrom($fcrPropertyCtaEmailFrom)
    {
        $this->fcrPropertyCtaEmailFrom = $fcrPropertyCtaEmailFrom;

        return $this;
    }

    /**
     * @return string
     */
    public function getFcrPropertyCtaEmailLabel()
    {
        return $this->fcrPropertyCtaEmailLabel;
    }

    /**
     * @param string $fcrPropertyCtaEmailLabel
     *
     * @return $this
     */
    public function setFcrPropertyCtaEmailLabel($fcrPropertyCtaEmailLabel)
    {
        $this->fcrPropertyCtaEmailLabel = $fcrPropertyCtaEmailLabel;

        return $this;
    }
}
