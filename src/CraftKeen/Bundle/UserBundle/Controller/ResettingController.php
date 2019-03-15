<?php

namespace CraftKeen\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\ResettingController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

/**
 * Controller managing the resetting of the password.
 */
class ResettingController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function sendEmailAction(Request $request)
    {
        // Performs csrf check and go on
        $token = new CsrfToken('authenticate', $request->request->get("_csrf_token"));
        /** @var CsrfTokenManager $csrf */
        $csrf = $this->get('security.csrf.token_manager');
        if (!$csrf->isTokenValid($token)) {
            throw new AccessDeniedException();
        }
        return parent::sendEmailAction($request);
    }
}
