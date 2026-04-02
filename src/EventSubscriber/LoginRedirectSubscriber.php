<?php

namespace App\EventSubscriber;

use App\Security\LoginTargetResolver;
use App\Security\RedirectTargetHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

/**
 * Redirige après connexion selon le rôle (doc: security, événements LoginSuccess).
 *
 * @see https://symfony.com/doc/current/security.html#redirecting-after-login
 */
class LoginRedirectSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly LoginTargetResolver $loginTargetResolver,
    ) {
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->attributes->get('_route') === 'app_register') {
            return;
        }

        $targetPath = RedirectTargetHelper::sanitize($request->request->get('_target_path'));
        if ($targetPath !== null) {
            $event->setResponse(new RedirectResponse($targetPath));

            return;
        }

        $route = $this->loginTargetResolver->routeNameForRoles($event->getUser()->getRoles());
        $event->setResponse(new RedirectResponse($this->urlGenerator->generate($route)));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }
}
