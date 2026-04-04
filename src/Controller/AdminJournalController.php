<?php

namespace App\Controller;

use App\Repository\ActivityLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminJournalController extends AbstractController
{
    #[Route('/journal', name: 'app_admin_journal', methods: ['GET'])]
    public function index(ActivityLogRepository $activityLogRepository): Response
    {
        return $this->render('admin/journal.html.twig', [
            'logs' => $activityLogRepository->findRecent(200),
        ]);
    }
}
