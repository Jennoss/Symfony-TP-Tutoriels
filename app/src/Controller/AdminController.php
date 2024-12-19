<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/user/{id}/edit', name: 'app_admin_user_edit')]
    public function editUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès.');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/user_edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/{id}/toggle-ban', name: 'app_admin_user_toggle_ban', methods: ['POST'])]
    public function toggleBan(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('toggle-ban'.$user->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_admin');
        }

        $user->setBanned(!$user->isBanned());
        $entityManager->flush();

        $status = $user->isBanned() ? 'banni' : 'débanni';
        $this->addFlash('success', "L'utilisateur a été $status avec succès.");

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/user/{id}/toggle-role', name: 'app_admin_user_toggle_role', methods: ['POST'])]
    public function toggleRole(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('toggle-role'.$user->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_admin');
        }

        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {
            $user->setRoles(['ROLE_USER']);
            $message = "Les droits administrateur ont été retirés.";
        } else {
            $user->setRoles(['ROLE_ADMIN']);
            $message = "Les droits administrateur ont été accordés.";
        }

        $entityManager->flush();
        $this->addFlash('success', $message);

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/user/{id}/delete', name: 'app_admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_admin');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès.');

        return $this->redirectToRoute('app_admin');
    }
}