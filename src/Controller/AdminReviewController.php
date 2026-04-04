<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ReviewRepository;
use App\Service\ActivityLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminReviewController extends AbstractController
{
    #[Route('/avis', name: 'app_admin_reviews', methods: ['GET'])]
    public function index(ReviewRepository $reviewRepository): Response
    {
        return $this->render('admin/reviews.html.twig', [
            'reviews' => $reviewRepository->findAllForModerationOrdered(),
        ]);
    }

    #[Route('/avis/{id}/visibilite', name: 'app_admin_review_toggle', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function toggleVisibility(int $id, Request $request, ReviewRepository $reviewRepository, EntityManagerInterface $em, ActivityLogger $activityLogger): Response
    {
        $review = $reviewRepository->find($id);
        if ($review === null) {
            throw $this->createNotFoundException();
        }

        if (!$this->isCsrfTokenValid('review_toggle_'.$id, (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $visible = $review->isVisible() ?? false;
        $review->setIsVisible(!$visible);
        $em->flush();

        $actor = $this->getUser();
        $activityLogger->log(
            $actor instanceof User ? $actor : null,
            'review.visibility_toggle',
            'Review',
            $review->getId(),
            $review->isVisible() ? 'visible' : 'hidden',
        );
        $em->flush();

        $this->addFlash('success', $review->isVisible() ? 'L’avis est maintenant visible sur la fiche ouvrage.' : 'L’avis est masqué.');

        return $this->redirectToRoute('app_admin_reviews');
    }
}
