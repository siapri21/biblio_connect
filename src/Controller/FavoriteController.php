<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\FavoriteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FavoriteController extends AbstractController
{
    #[Route('/favoris', name: 'app_favorites')]
    #[IsGranted('ROLE_USER')]
    public function index(FavoriteRepository $favoriteRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $favorites = $favoriteRepository->findByMemberOrdered($user);

        return $this->render('favorite/index.html.twig', [
            'favorites' => $favorites,
        ]);
    }
}
