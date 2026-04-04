<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ReservationRepository;
use App\Service\ActivityLogger;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/bibliotheque/reservations/{id}/valider', name: 'app_staff_reservation_confirm', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_LIBRARIAN')]
    public function confirm(Request $request, Reservation $reservation, EntityManagerInterface $em, ActivityLogger $activityLogger): Response
    {
        if (!$this->isCsrfTokenValid('staff_confirm_'.$reservation->getId(), (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        if ($reservation->getStatus() !== 'pending') {
            $this->addFlash('info', 'Cette demande a déjà été traitée.');

            return $this->redirectToReferrerReservations($request);
        }

        $reservation->setStatus('confirmed');
        $actor = $this->getUser();
        $activityLogger->log(
            $actor instanceof User ? $actor : null,
            'reservation.confirm',
            'Reservation',
            $reservation->getId(),
            'Livre #'.$reservation->getBook()?->getId(),
        );
        $em->flush();

        $this->addFlash('success', 'Réservation acceptée. L’usager peut retirer l’ouvrage à la date prévue.');

        return $this->redirectToReferrerReservations($request);
    }

    #[Route('/bibliotheque/reservations/{id}/refuser', name: 'app_staff_reservation_reject', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_LIBRARIAN')]
    public function reject(Request $request, Reservation $reservation, EntityManagerInterface $em, ActivityLogger $activityLogger): Response
    {
        if (!$this->isCsrfTokenValid('staff_reject_'.$reservation->getId(), (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        if ($reservation->getStatus() !== 'pending') {
            $this->addFlash('info', 'Cette demande ne peut plus être refusée.');

            return $this->redirectToReferrerReservations($request);
        }

        $reservation->setStatus('cancelled');
        $actor = $this->getUser();
        $activityLogger->log(
            $actor instanceof User ? $actor : null,
            'reservation.reject',
            'Reservation',
            $reservation->getId(),
            null,
        );
        $em->flush();

        $this->addFlash('success', 'La réservation a été refusée. L’usager en est informé dans son espace.');

        return $this->redirectToReferrerReservations($request);
    }

    #[Route('/bibliotheque/reservations/{id}/terminer', name: 'app_staff_reservation_complete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_LIBRARIAN')]
    public function complete(Request $request, Reservation $reservation, EntityManagerInterface $em, ActivityLogger $activityLogger): Response
    {
        if (!$this->isCsrfTokenValid('staff_complete_'.$reservation->getId(), (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        if ($reservation->getStatus() !== 'confirmed') {
            $this->addFlash('info', 'Seul un prêt confirmé peut être clôturé.');

            return $this->redirectToReferrerReservations($request);
        }

        $reservation->setStatus('completed');
        $actor = $this->getUser();
        $activityLogger->log(
            $actor instanceof User ? $actor : null,
            'reservation.complete',
            'Reservation',
            $reservation->getId(),
            null,
        );
        $em->flush();

        $this->addFlash('success', 'Le prêt est marqué comme terminé (retour enregistré).');

        return $this->redirectToReferrerReservations($request);
    }

    private function redirectToReferrerReservations(Request $request): Response
    {
        $back = $request->request->get('_back');
        if ($back === 'pending') {
            return $this->redirectToRoute('app_librarian_reservations', ['status' => 'pending']);
        }

        return $this->redirectToRoute('app_librarian_reservations');
    }
}
