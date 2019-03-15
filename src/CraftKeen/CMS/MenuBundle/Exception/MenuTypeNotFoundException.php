<?php

namespace CraftKeen\CMS\MenuBundle\Exception;

use Exception;

class MenuTypeNotFoundException extends \Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct(sprintf("Menu type '%s' can not be found", $message), $code, $previous);
    }
}
