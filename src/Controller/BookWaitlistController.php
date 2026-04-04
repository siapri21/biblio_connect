<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookWaitlist;
use App\Entity\User;
use App\Repository\BookWaitlistRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BookWaitlistController extends AbstractController
{
    #[Route('/ouvrage/{id}/liste-attente', name: 'app_book_waitlist_join', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function join(
        Request $request,
        Book $book,
        EntityManagerInterface $em,
        BookWaitlistRepository $bookWaitlistRepository,
        ReservationRepository $reservationRepository,
    ): Response {
        if (!$this->isCsrfTokenValid('waitlist_join', (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($bookWaitlistRepository->findOneByMemberAndBook($user, $book) !== null) {
            $this->addFlash('info', 'Vous êtes déjà inscrit sur la liste d’attente pour cet ouvrage.');

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        if ($reservationRepository->hasActiveReservationForMemberAndBook($user, $book)) {
            $this->addFlash('info', 'Vous avez déjà une réservation active pour cet ouvrage.');

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        $active = $reservationRepository->countActiveForBook($book);
        if ($book->getStock() > 0 && $active < $book->getStock()) {
            $this->addFlash('info', 'Des exemplaires sont disponibles : vous pouvez réserver directement.');

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        $entry = (new BookWaitlist())
            ->setMember($user)
            ->setBook($book)
            ->setCreatedAt(new \DateTimeImmutable());

        $em->persist($entry);
        $em->flush();

        $pos = $bookWaitlistRepository->getQueuePosition($entry);
        $this->addFlash('success', sprintf(
            'Vous êtes inscrit sur la liste d’attente (position approximative : %d). Nous vous contacterons lorsqu’un exemplaire sera libre.',
            $pos,
        ));

        return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
    }

    #[Route('/liste-attente/{id}/quitter', name: 'app_book_waitlist_leave', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function leave(
        Request $request,
        BookWaitlist $bookWaitlist,
        EntityManagerInterface $em,
    ): Response {
        if (!$this->isCsrfTokenValid('waitlist_leave', (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        /** @var User $user */
        $user = $this->getUser();
        if ($bookWaitlist->getMember()?->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException();
        }

        $bookId = $bookWaitlist->getBook()?->getId();
        $em->remove($bookWaitlist);
        $em->flush();

        $this->addFlash('success', 'Vous avez quitté la liste d’attente.');

        return $this->redirectToRoute('app_book_show', ['id' => $bookId ?? 0]);
    }
}
