<?php

namespace App\Security;

/**
 * Détermine la route de tableau de bord après connexion selon les rôles.
 */
final class LoginTargetResolver
{
    public function routeNameForRoles(array $roles): string
    {
        if (\in_array('ROLE_ADMIN', $roles, true)) {
            return 'app_admin_dashboard';
        }

        if (\in_array('ROLE_LIBRARIAN', $roles, true)) {
            return 'app_librarian_dashboard';
        }

        return 'app_user_dashboard';
    }
}
