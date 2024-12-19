<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Chapitre;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/chapitre/{id}/new', name: 'app_commentaire_new', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, Chapitre $chapitre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_BANNED')) {
            return $this->render('banned.html.twig');
        }

        $commentaire = new Commentaire();
        $commentaire->setChapitre($chapitre);
        $commentaire->setCreatedAt(new \DateTime());

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté.');
            return $this->redirectToRoute('app_chapitre_show', ['id' => $chapitre->getId()]);
        }

        return $this->redirectToRoute('app_chapitre_show', [
            'id' => $chapitre->getId(),
            'commentForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de modifier ce commentaire.');
        }

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le commentaire a été modifié.');
            return $this->redirectToRoute('app_chapitre_show', ['id' => $commentaire->getChapitre()->getId()]);
        }

        return $this->render('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de supprimer ce commentaire.');
        }

        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $chapitreId = $commentaire->getChapitre()->getId();
            $entityManager->remove($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Le commentaire a été supprimé.');
            return $this->redirectToRoute('app_chapitre_show', ['id' => $chapitreId]);
        }

        return $this->redirectToRoute('app_chapitre_show', ['id' => $commentaire->getChapitre()->getId()]);
    }
}