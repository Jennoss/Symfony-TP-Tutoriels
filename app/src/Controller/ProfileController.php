<?php

namespace App\Controller;

use App\Form\ProfileEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_BANNED')) {
            return $this->render('banned.html.twig');
        }

        return $this->render('profile/index.html.twig');
    }

    #[Route('/edit', name: 'app_profile_edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        if ($this->isGranted('ROLE_BANNED')) {
            return $this->render('banned.html.twig');
        }

        $user = $this->getUser();
        $form = $this->createForm(ProfileEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le changement de mot de passe
            $newPassword = $form->get('newPassword')->getData();
            if ($newPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès !');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete', name: 'app_profile_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isGranted('ROLE_BANNED')) {
            return $this->render('banned.html.twig');
        }

        if (!$this->isCsrfTokenValid('delete-account', $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide');
            return $this->redirectToRoute('app_profile');
        }

        $user = $this->getUser();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_logout');
    }
}