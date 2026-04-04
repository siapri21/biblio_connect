<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/catalogue', name: 'app_catalog', methods: ['GET'])]
    public function catalog(
        Request $request,
        BookRepository $bookRepository,
        LibraryRepository $libraryRepository,
    ): Response {
        $q = $request->query->getString('q');
        $category = $request->query->get('category');
        $author = $request->query->get('author');
        $language = $request->query->get('language');
        $libraryId = $request->query->get('library');
        $available = $request->query->get('available');
        $sort = $request->query->getString('sort', 'title_asc');

        $allowedSort = ['title_asc', 'title_desc', 'author_asc', 'author_desc'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'title_asc';
        }

        $libraryIdInt = null;
        if (is_string($libraryId) && ctype_digit($libraryId)) {
            $libraryIdInt = (int) $libraryId;
        }

        $availableOnly = null;
        if ($available === '1' || $available === 'true') {
            $availableOnly = true;
        }

        return $this->render('catalog/index.html.twig', [
            'books' => $bookRepository->searchCatalog(
                $q,
                is_string($category) ? $category : null,
                is_string($author) ? $author : null,
                is_string($language) ? $language : null,
                $libraryIdInt,
                $availableOnly,
                $sort,
            ),
            'searchQuery' => $q,
            'filterCategory' => is_string($category) ? $category : '',
            'filterAuthor' => is_string($author) ? $author : '',
            'filterLanguage' => is_string($language) ? $language : '',
            'filterLibraryId' => $libraryIdInt ?? 0,
            'filterAvailableOnly' => $availableOnly === true,
            'sort' => $sort,
            'libraries' => $libraryRepository->findAllOrderedByName(),
            'categories' => $bookRepository->findDistinctCategories(),
        ]);
    }
}
