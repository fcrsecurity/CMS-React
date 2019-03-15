<?php

namespace CraftKeen\Bundle\ComponentBundle\Model;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

interface SecurityContextAwareInterface
{
    /**
     * @param TokenStorageInterface $tokenStorage
     *
     * @return $this
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage);

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     *
     * @return $this
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker);
}
