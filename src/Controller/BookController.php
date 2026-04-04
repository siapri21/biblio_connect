<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Favorite;
use App\Entity\Reservation;
use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewFormType;
use App\Repository\BookWaitlistRepository;
use App\Repository\FavoriteRepository;
use App\Repository\ReservationRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BookController extends AbstractController
{
    private const LOAN_DAYS = 21;

    private const RESERVATION_MAX_AHEAD_DAYS = 60;

    #[Route('/ouvrage/{id}', name: 'app_book_show', requirements: ['id' => '\d+'])]
    public function show(
        Book $book,
        FavoriteRepository $favoriteRepository,
        ReviewRepository $reviewRepository,
        ReservationRepository $reservationRepository,
        BookWaitlistRepository $bookWaitlistRepository,
    ): Response {
        $user = $this->getUser();
        $isFavorite = false;
        $userReview = null;
        $reviewForm = null;
        $userHasActiveReservation = false;
        $userWaitlistEntry = null;
        $waitlistTotal = 0;
        $canJoinWaitlist = false;
        $waitlistTotal = $bookWaitlistRepository->countByBook($book);

        if ($user instanceof User) {
            $isFavorite = null !== $favoriteRepository->findOneByMemberAndBook($user, $book);
            $userReview = $reviewRepository->findOneByUserAndBook($user, $book);
            $userHasActiveReservation = $reservationRepository->hasActiveReservationForMemberAndBook($user, $book);
            $userWaitlistEntry = $bookWaitlistRepository->findOneByMemberAndBook($user, $book);
            $activeRes = $reservationRepository->countActiveForBook($book);
            $canJoinWaitlist = !$userHasActiveReservation
                && $userWaitlistEntry === null
                && ($book->getStock() < 1 || $activeRes >= $book->getStock());
            if ($userReview === null) {
                $reviewForm = $this->createForm(ReviewFormType::class)->createView();
            }
        }

        $allVisibleReviews = $reviewRepository->findVisibleByBookOrdered($book);
        $reviewCount = count($allVisibleReviews);
        $reviews = $allVisibleReviews;
        if ($user instanceof User) {
            $uid = $user->getId();
            $reviews = array_values(array_filter(
                $reviews,
                static fn (Review $r) => $r->getReservedBy()?->getId() !== $uid,
            ));
        }
        $avgRating = $reviewRepository->averageRatingForBook($book);

        $today = new \DateTimeImmutable('today');
        $maxDate = $today->modify('+'.self::RESERVATION_MAX_AHEAD_DAYS.' days');

        return $this->render('book/show.html.twig', [
            'book' => $book,
            'isFavorite' => $isFavorite,
            'reviews' => $reviews,
            'reviewCount' => $reviewCount,
            'avgRating' => $avgRating,
            'userReview' => $userReview,
            'reviewForm' => $reviewForm,
            'userHasActiveReservation' => $userHasActiveReservation,
            'reservationMinDate' => $today->format('Y-m-d'),
            'reservationMaxDate' => $maxDate->format('Y-m-d'),
            'userWaitlistEntry' => $userWaitlistEntry,
            'waitlistTotal' => $waitlistTotal,
            'canJoinWaitlist' => $canJoinWaitlist,
        ]);
    }

    #[Route('/ouvrage/{id}/favoris', name: 'app_book_favorite_toggle', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function toggleFavorite(
        Request $request,
        Book $book,
        EntityManagerInterface $em,
        FavoriteRepository $favoriteRepository,
    ): Response {
        if (!$this->isCsrfTokenValid('favorite_toggle', (string) $request->request->get('favorite_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        /** @var User $user */
        $user = $this->getUser();
        $existing = $favoriteRepository->findOneByMemberAndBook($user, $book);

        if ($existing !== null) {
            $em->remove($existing);
            $this->addFlash('success', 'Livre retiré de vos favoris.');
        } else {
            $favorite = (new Favorite())
                ->setMember($user)
                ->setBook($book)
                ->setCreateAt(new \DateTimeImmutable());
            $em->persist($favorite);
            $this->addFlash('success', 'Livre ajouté à vos favoris.');
        }

        $em->flush();

        return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
    }

    #[Route('/ouvrage/{id}/reserver', name: 'app_book_reserve', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function reserve(
        Request $request,
        Book $book,
        EntityManagerInterface $em,
        ReservationRepository $reservationRepository,
    ): Response {
        if (!$this->isCsrfTokenValid('reserve_book', (string) $request->request->get('reserve_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        /** @var User $user */
        $user = $this->getUser();

        $pickupRaw = (string) $request->request->get('pickup_date');
        $pickup = \DateTimeImmutable::createFromFormat('Y-m-d', $pickupRaw);
        if ($pickup === false || $pickup->format('Y-m-d') !== $pickupRaw) {
            $this->addFlash('danger', 'Date de retrait invalide.');

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        $pickup = $pickup->setTime(0, 0);
        $today = (new \DateTimeImmutable('today'))->setTime(0, 0);
        $maxDay = $today->modify('+'.self::RESERVATION_MAX_AHEAD_DAYS.' days');

        if ($pickup < $today || $pickup > $maxDay) {
            $this->addFlash('danger', 'La date doit être entre aujourd’hui et '.self::RESERVATION_MAX_AHEAD_DAYS.' jours.');

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        if ($book->getStock() < 1) {
            $this->addFlash('danger', 'Cet ouvrage n’est plus disponible (stock épuisé).');

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        if ($reservationRepository->hasActiveReservationForMemberAndBook($user, $book)) {
            $this->addFlash('danger', 'Vous avez déjà une réservation en cours pour cet ouvrage. Attendez la fin du prêt ou l’annulation avant d’en créer une nouvelle.');

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        $active = $reservationRepository->countActiveForBook($book);
        if ($active >= $book->getStock()) {
            $this->addFlash('danger', 'Tous les exemplaires sont déjà réservés pour cette période. Réessayez plus tard.');

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        $endAt = $pickup->modify('+'.self::LOAN_DAYS.' days');

        $reservation = (new Reservation())
            ->setMember($user)
            ->setBook($book)
            ->setStartAt($pickup)
            ->setEndAt($endAt)
            ->setStatus('pending')
            ->setCreatedAt(new \DateTimeImmutable());

        $em->persist($reservation);
        $em->flush();

        $libLabel = $book->getLibrary()?->getLabel() ?? 'la bibliothèque de rattachement';
        $this->addFlash(
            'success',
            'Demande enregistrée : elle doit être validée par la bibliothèque. Retrait prévu à '.$libLabel.' à partir du '.$pickup->format('d/m/Y').' (prêt de '.self::LOAN_DAYS.' jours) une fois acceptée.',
        );

        return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
    }

    #[Route('/ouvrage/{id}/avis', name: 'app_book_review', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function review(
        Request $request,
        Book $book,
        EntityManagerInterface $em,
        ReviewRepository $reviewRepository,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        if (null !== $reviewRepository->findOneByUserAndBook($user, $book)) {
            $this->addFlash('info', 'Vous avez déjà publié un avis sur cet ouvrage.');

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        $form = $this->createForm(ReviewFormType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addFlash('danger', 'Impossible d’enregistrer votre avis. Vérifiez les champs.');

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        $data = $form->getData();

        $review = (new Review())
            ->setReservedBy($user)
            ->setBook($book)
            ->setRating((int) $data['rating'])
            ->setComment((string) $data['comment'])
            ->setIsVisible(true)
            ->setCreatedAt(new \DateTimeImmutable());

        $em->persist($review);
        $em->flush();

        $this->addFlash('success', 'Votre avis a été publié.');

        return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
    }
}
