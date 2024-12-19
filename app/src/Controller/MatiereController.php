<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/matiere')]
class MatiereController extends AbstractController
{
    #[Route('', name: 'app_matiere_index', methods: ['GET'])]
    public function index(MatiereRepository $matiereRepository): Response
    {
        if ($this->isGranted('ROLE_BANNED')) {
            return $this->render('banned.html.twig');
        }

        return $this->render('matiere/index.html.twig', [
            'matieres' => $matiereRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_matiere_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($matiere);
            $entityManager->flush();

            $this->addFlash('success', 'La matière a été créée avec succès.');
            return $this->redirectToRoute('app_matiere_index');
        }

        return $this->render('matiere/new.html.twig', [
            'matiere' => $matiere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_matiere_show', methods: ['GET'])]
    public function show(Matiere $matiere): Response
    {
        if ($this->isGranted('ROLE_BANNED')) {
            return $this->render('banned.html.twig');
        }

        return $this->render('matiere/show.html.twig', [
            'matiere' => $matiere,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_matiere_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Matiere $matiere, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La matière a été modifiée avec succès.');
            return $this->redirectToRoute('app_matiere_show', ['id' => $matiere->getId()]);
        }

        return $this->render('matiere/edit.html.twig', [
            'matiere' => $matiere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_matiere_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Matiere $matiere, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$matiere->getId(), $request->request->get('_token'))) {
            $entityManager->remove($matiere);
            $entityManager->flush();
            $this->addFlash('success', 'La matière a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_matiere_index');
    }
}