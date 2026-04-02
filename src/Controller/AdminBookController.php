<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\LibraryRepository;
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
    public function new(Request $request, EntityManagerInterface $em, LibraryRepository $libraryRepository): Response
    {
        $libraries = $libraryRepository->findAllOrderedByName();

        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
                throw $this->createAccessDeniedException('Jeton CSRF invalide.');
            }

            $library = $libraryRepository->find((int) $request->request->get('library_id'));
            if ($library === null) {
                $this->addFlash('danger', 'Bibliothèque de rattachement invalide.');

                return $this->render('admin/book_new.html.twig', ['libraries' => $libraries]);
            }

            $book = (new Book())
                ->setTitle((string) $request->request->get('title'))
                ->setAuthor((string) $request->request->get('author'))
                ->setCategory((string) $request->request->get('category'))
                ->setLanguage((string) $request->request->get('language'))
                ->setStock((int) $request->request->get('stock'))
                ->setDescription($request->request->get('description') ? (string) $request->request->get('description') : null)
                ->setLibrary($library);

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('admin/book_new.html.twig', ['libraries' => $libraries]);
    }

    #[Route('/ouvrages', name: 'app_admin_book_list', methods: ['GET'])]
    public function list(BookRepository $books): Response
    {
        return $this->render('admin/book_list.html.twig', [
            'books' => $books->findAll(),
        ]);
    }
}
