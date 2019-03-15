<?php

namespace CraftKeen\Bundle\TranslationBundle\Exception;

use Exception;

class LanguageNotFoundException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($locale, $code = 0, Exception $previous = null)
    {
        parent::__construct(sprintf('Language with locale "%s" not found', $locale), $code, $previous);
    }
}
