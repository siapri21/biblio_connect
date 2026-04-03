<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

/**
 * Si un utilisateur connecté (non admin) ouvre /admin, redirection vers son espace avec message clair.
 */
final class AdminAccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestStack $requestStack,
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        if ($request->getPathInfo() !== '/admin') {
            return null;
        }

        $token = $this->tokenStorage->getToken();
        if ($token === null || !\is_object($user = $token->getUser())) {
            return null;
        }

        if (!$user instanceof User) {
            return null;
        }

        $roles = $user->getRoles();
        if (\in_array('ROLE_ADMIN', $roles, true)) {
            return null;
        }

        $session = $this->requestStack->getSession();
        if (\in_array('ROLE_LIBRARIAN', $roles, true)) {
            $session->getFlashBag()->add(
                'info',
                'L’interface d’administration complète est réservée aux comptes administrateur. Vous êtes sur l’espace bibliothécaire.',
            );

            return new RedirectResponse($this->urlGenerator->generate('app_librarian_dashboard'));
        }

        $session->getFlashBag()->add(
            'info',
            'L’administration est réservée aux administrateurs. Voici votre espace personnel.',
        );

        return new RedirectResponse($this->urlGenerator->generate('app_user_dashboard'));
    }
}
