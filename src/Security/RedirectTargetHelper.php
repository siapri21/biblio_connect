<?php

namespace App\Security;

/**
 * Limite les redirections ouvertes après login (chemins relatifs internes uniquement).
 */
final class RedirectTargetHelper
{
    public static function sanitize(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $path = trim($path);
        if (!str_starts_with($path, '/') || str_starts_with($path, '//')) {
            return null;
        }

        if (preg_match('/[\r\n\x00]/', $path)) {
            return null;
        }

        if (str_contains($path, ':')) {
            return null;
        }

        return $path;
    }
}
