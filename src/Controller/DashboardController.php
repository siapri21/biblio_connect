<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/espace', name: 'app_user_dashboard')]
    public function userSpace(): Response
    {
        return $this->render('dashboard/user.html.twig');
    }

    #[Route('/bibliotheque', name: 'app_librarian_dashboard')]
    public function librarian(): Response
    {
        return $this->render('dashboard/librarian.html.twig');
    }

    #[Route('/admin', name: 'app_admin_dashboard')]
    public function admin(): Response
    {
        return $this->render('dashboard/admin.html.twig');
    }
}
