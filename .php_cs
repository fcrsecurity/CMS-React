<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('var/cache')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;