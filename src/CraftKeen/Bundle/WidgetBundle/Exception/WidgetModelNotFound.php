<?php

namespace CraftKeen\Bundle\WidgetBundle\Exception;

use Exception;

class WidgetModelNotFound extends \Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            sprintf('Unable to find proper model for "%s"', $message),
            $code,
            $previous
        );
    }
}
