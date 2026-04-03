<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\FavoriteRepository;
use App\Repository\ReservationRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/espace', name: 'app_user_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function userSpace(
        FavoriteRepository $favoriteRepository,
        ReservationRepository $reservationRepository,
        ReviewRepository $reviewRepository,
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('dashboard/user.html.twig', [
            'favorites' => $favoriteRepository->findByMemberOrdered($user),
            'reservations' => $reservationRepository->findByMemberOrdered($user),
            'reviews' => $reviewRepository->findByMemberOrdered($user),
        ]);
    }

    #[Route('/bibliotheque', name: 'app_librarian_dashboard')]
    #[IsGranted('ROLE_LIBRARIAN')]
    public function librarian(
        BookRepository $bookRepository,
        ReservationRepository $reservationRepository,
        ReviewRepository $reviewRepository,
    ): Response {
        return $this->render('dashboard/librarian.html.twig', [
            'statBooks' => $bookRepository->count([]),
            'statReservationsPending' => $reservationRepository->countByStatus('pending'),
            'statReviewsVisible' => $reviewRepository->countVisible(),
        ]);
    }

    #[Route('/admin', name: 'app_admin_dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(
        BookRepository $bookRepository,
        ReservationRepository $reservationRepository,
        ReviewRepository $reviewRepository,
    ): Response {
        return $this->render('dashboard/admin.html.twig', [
            'statBooks' => $bookRepository->count([]),
            'statReservationsPending' => $reservationRepository->countByStatus('pending'),
            'statReviewsVisible' => $reviewRepository->countVisible(),
        ]);
    }
}
