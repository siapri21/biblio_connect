<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\LibraryRepository;
use App\Service\ActivityLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/catalogue')]
#[IsGranted('ROLE_LIBRARIAN')]
class AdminBookController extends AbstractController
{
    #[Route('/ouvrage/nouveau', name: 'app_admin_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, LibraryRepository $libraryRepository, ActivityLogger $activityLogger): Response
    {
        $libraries = $libraryRepository->findAllOrderedByName();

        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
                throw $this->createAccessDeniedException('Jeton CSRF invalide.');
            }

            $book = new Book();
            $error = $this->populateBookFromRequest($book, $request, $libraryRepository);
            if ($error !== null) {
                $this->addFlash('danger', $error);

                return $this->render('admin/book_form.html.twig', [
                    'libraries' => $libraries,
                    'book' => $book,
                ]);
            }

            $em->persist($book);
            $em->flush();

            $u = $this->getUser();
            $activityLogger->log($u instanceof User ? $u : null, 'book.create', 'Book', $book->getId(), $book->getTitle());
            $em->flush();

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('admin/book_form.html.twig', [
            'libraries' => $libraries,
            'book' => null,
        ]);
    }

    #[Route('/ouvrage/{id}/modifier', name: 'app_admin_book_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, BookRepository $bookRepository, EntityManagerInterface $em, LibraryRepository $libraryRepository, ActivityLogger $activityLogger): Response
    {
        $book = $bookRepository->find($id);
        if (!$book instanceof Book) {
            throw $this->createNotFoundException();
        }

        $libraries = $libraryRepository->findAllOrderedByName();

        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
                throw $this->createAccessDeniedException('Jeton CSRF invalide.');
            }

            $error = $this->populateBookFromRequest($book, $request, $libraryRepository);
            if ($error !== null) {
                $this->addFlash('danger', $error);

                return $this->render('admin/book_form.html.twig', [
                    'libraries' => $libraries,
                    'book' => $book,
                ]);
            }

            $em->flush();

            $u = $this->getUser();
            $activityLogger->log($u instanceof User ? $u : null, 'book.update', 'Book', $book->getId(), $book->getTitle());
            $em->flush();

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('admin/book_form.html.twig', [
            'libraries' => $libraries,
            'book' => $book,
        ]);
    }

    #[Route('/ouvrages', name: 'app_admin_book_list', methods: ['GET'])]
    public function list(BookRepository $books): Response
    {
        return $this->render('admin/book_list.html.twig', [
            'books' => $books->findAll(),
        ]);
    }

    private function populateBookFromRequest(Book $book, Request $request, LibraryRepository $libraryRepository): ?string
    {
        $library = $libraryRepository->find((int) $request->request->get('library_id'));
        if ($library === null) {
            return 'Bibliothèque de rattachement invalide.';
        }

        $cover = trim((string) $request->request->get('cover_image_path'));
        $desc = $request->request->get('description');
        $desc = is_string($desc) && $desc !== '' ? $desc : null;

        $book
            ->setTitle(trim((string) $request->request->get('title')))
            ->setAuthor(trim((string) $request->request->get('author')))
            ->setCategory(trim((string) $request->request->get('category')))
            ->setLanguage(trim((string) $request->request->get('language')))
            ->setStock(max(0, (int) $request->request->get('stock')))
            ->setDescription($desc)
            ->setCoverImagePath($cover !== '' ? $cover : null)
            ->setLibrary($library);

        return null;
    }
}
