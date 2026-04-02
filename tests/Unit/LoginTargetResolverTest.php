<?php

namespace App\Tests\Unit;

use App\Security\LoginTargetResolver;
use PHPUnit\Framework\TestCase;

class LoginTargetResolverTest extends TestCase
{
    public function testAdminRoute(): void
    {
        $r = new LoginTargetResolver();
        self::assertSame('app_admin_dashboard', $r->routeNameForRoles(['ROLE_USER', 'ROLE_ADMIN']));
    }

    public function testLibrarianRoute(): void
    {
        $r = new LoginTargetResolver();
        self::assertSame('app_librarian_dashboard', $r->routeNameForRoles(['ROLE_USER', 'ROLE_LIBRARIAN']));
    }

    public function testUserRoute(): void
    {
        $r = new LoginTargetResolver();
        self::assertSame('app_user_dashboard', $r->routeNameForRoles(['ROLE_USER']));
    }
}
