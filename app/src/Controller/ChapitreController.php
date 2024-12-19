<?php

namespace App\Controller;

use App\Entity\Chapitre;
use App\Entity\Commentaire;
use App\Entity\Tutoriel;
use App\Form\ChapitreType;
use App\Form\CommentaireType;
use App\Repository\ChapitreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/chapitre')]
class ChapitreController extends AbstractController
{
    #[Route('/{id}', name: 'app_chapitre_show', methods: ['GET'])]
    public function show(Chapitre $chapitre): Response
    {
        if ($this->isGranted('ROLE_BANNED')) {
            return $this->render('banned.html.twig');
        }

        $commentForm = $this->createForm(CommentaireType::class, new Commentaire());

        return $this->render('chapitre/show.html.twig', [
            'chapitre' => $chapitre,
            'commentForm' => $commentForm,
        ]);
    }

    #[Route('/tutoriel/{tutoriel}/new', name: 'app_chapitre_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager, Tutoriel $tutoriel): Response
    {
        $chapitre = new Chapitre();
        $chapitre->setTutoriel($tutoriel);
        
        $form = $this->createForm(ChapitreType::class, $chapitre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chapitre);
            $entityManager->flush();

            $this->addFlash('success', 'Le chapitre a été créé avec succès.');
            return $this->redirectToRoute('app_tutoriel_show', ['id' => $tutoriel->getId()]);
        }

        return $this->render('chapitre/new.html.twig', [
            'chapitre' => $chapitre,
            'tutoriel' => $tutoriel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chapitre_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Chapitre $chapitre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChapitreType::class, $chapitre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le chapitre a été modifié avec succès.');
            return $this->redirectToRoute('app_chapitre_show', ['id' => $chapitre->getId()]);
        }

        return $this->render('chapitre/edit.html.twig', [
            'chapitre' => $chapitre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_chapitre_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Chapitre $chapitre, EntityManagerInterface $entityManager): Response
    {
        $tutorielId = $chapitre->getTutoriel()->getId();
        
        if ($this->isCsrfTokenValid('delete'.$chapitre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chapitre);
            $entityManager->flush();
            $this->addFlash('success', 'Le chapitre a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_tutoriel_show', ['id' => $tutorielId]);
    }
}