<?php

namespace CraftKeen\Bundle\ComponentBundle\Traits;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

trait SecurityContextAwareTrait
{
    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return TokenInterface
     */
    public function getToken()
    {
        return $this->tokenStorage->getToken();
    }

    /**
     * @param mixed $attributes
     * @param mixed|null $object
     *
     * @return bool
     */
    public function isGranted($attributes, $object = null)
    {
        return $this->authorizationChecker->isGranted($attributes, $object);
    }
}
