<?php

namespace CraftKeen\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CraftKeenUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
