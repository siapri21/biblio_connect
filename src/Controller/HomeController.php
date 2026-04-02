<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/catalogue', name: 'app_catalog')]
    public function catalog(BookRepository $bookRepository): Response
    {
        return $this->render('catalog/index.html.twig', [
            'books' => $bookRepository->findBy([], ['title' => 'ASC'], 48),
        ]);
    }
}
