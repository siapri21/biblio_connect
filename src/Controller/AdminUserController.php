<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/utilisateurs')]
#[IsGranted('ROLE_ADMIN')]
class AdminUserController extends AbstractController
{
    #[Route('', name: 'app_admin_users', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAllOrderedByEmail(),
        ]);
    }

    #[Route('/{id}/roles', name: 'app_admin_user_roles', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function updateRoles(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($id);
        if (!$user instanceof User) {
            throw $this->createNotFoundException();
        }

        if (!$this->isCsrfTokenValid('user_roles_'.$id, (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $primary = (string) $request->request->get('primary_role');
        $newRoles = match ($primary) {
            'librarian' => ['ROLE_LIBRARIAN'],
            'admin' => ['ROLE_ADMIN'],
            default => [],
        };

        $wasAdmin = in_array('ROLE_ADMIN', $user->getRoles(), true);
        $willBeAdmin = $newRoles === ['ROLE_ADMIN'];

        if ($wasAdmin && !$willBeAdmin && $userRepository->countUsersWithRole('ROLE_ADMIN') <= 1) {
            $this->addFlash('danger', 'Impossible de retirer le dernier compte administrateur.');

            return $this->redirectToRoute('app_admin_users');
        }

        $user->setRoles($newRoles);
        $em->flush();

        $this->addFlash('success', 'Rôle mis à jour pour '.$user->getUserIdentifier().'.');

        return $this->redirectToRoute('app_admin_users');
    }
}
