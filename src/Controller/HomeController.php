<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/catalogue', name: 'app_catalog', methods: ['GET'])]
    public function catalog(Request $request, BookRepository $bookRepository): Response
    {
        $q = $request->query->getString('q');

        return $this->render('catalog/index.html.twig', [
            'books' => $bookRepository->searchCatalog($q),
            'searchQuery' => $q,
        ]);
    }
}
