<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReservationController extends AbstractController
{
    #[Route('/espace/reservation/{id}/annuler', name: 'app_reservation_cancel', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function cancel(
        Request $request,
        Reservation $reservation,
        EntityManagerInterface $em,
    ): Response {
        if (!$this->isCsrfTokenValid('cancel_reservation', (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        if ($reservation->getMember()?->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException();
        }

        if (!$reservation->canBeCancelledByMember()) {
            $this->addFlash('info', 'Cette réservation ne peut plus être annulée.');

            return $this->redirectToRoute('app_user_dashboard');
        }

        $reservation->setStatus('cancelled');
        $em->flush();

        $this->addFlash('success', 'Votre réservation a été annulée.');

        return $this->redirectToRoute('app_user_dashboard');
    }
}
