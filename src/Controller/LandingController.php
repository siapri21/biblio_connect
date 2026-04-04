<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LandingController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(BookRepository $bookRepository, ReviewRepository $reviewRepository): Response
    {
        $forCarousel = $bookRepository->findFeaturedForHome(10);
        $carouselBooks = [];
        foreach ($forCarousel as $book) {
            $carouselBooks[] = [
                'book' => $book,
                'avgRating' => $reviewRepository->averageRatingForBook($book),
                'reviewCount' => $reviewRepository->countVisibleForBook($book),
            ];
        }

        return $this->render('landing/index.html.twig', [
            'highlightBooks' => $bookRepository->findFeaturedForHome(8),
            'carouselBooks' => $carouselBooks,
            'statBookCount' => $bookRepository->count([]),
            'statCategoryCount' => $bookRepository->countDistinctCategories(),
            'filterCategories' => $bookRepository->findDistinctCategories(),
            'filterAuthors' => $bookRepository->findDistinctAuthors(),
            'filterLanguages' => $bookRepository->findDistinctLanguages(),
        ]);
    }
}
