<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Historique des réservations pour le personnel (ROLE_LIBRARIAN, donc aussi les admins).
 */
class LibrarianReservationController extends AbstractController
{
    #[Route('/bibliotheque/reservations', name: 'app_librarian_reservations', methods: ['GET'])]
    #[IsGranted('ROLE_LIBRARIAN')]
    public function index(Request $request, ReservationRepository $reservationRepository): Response
    {
        $status = $request->query->get('status');
        $allowed = ['pending', 'confirmed', 'cancelled', 'completed'];
        $filterStatus = is_string($status) && in_array($status, $allowed, true) ? $status : null;

        return $this->render('librarian/reservations.html.twig', [
            'reservations' => $reservationRepository->findAllForStaffOrdered($filterStatus),
            'filterStatus' => $filterStatus,
        ]);
    }
}
