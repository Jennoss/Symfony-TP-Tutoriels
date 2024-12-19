<?php

namespace App\Controller;

use App\Entity\Tutoriel;
use App\Form\TutorielType;
use App\Repository\TutorielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/tutoriel')]
class TutorielController extends AbstractController
{
    #[Route('', name: 'app_tutoriel_index', methods: ['GET'])]
    public function index(TutorielRepository $tutorielRepository): Response
    {
        if ($this->isGranted('ROLE_BANNED')) {
            return $this->render('banned.html.twig');
        }

        return $this->render('tutoriel/index.html.twig', [
            'tutoriels' => $tutorielRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_tutoriel_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tutoriel = new Tutoriel();
        $tutoriel->setCreatedAt(new \DateTime());
        
        $form = $this->createForm(TutorielType::class, $tutoriel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tutoriel);
            $entityManager->flush();

            $this->addFlash('success', 'Le tutoriel a été créé avec succès.');
            return $this->redirectToRoute('app_tutoriel_show', ['id' => $tutoriel->getId()]);
        }

        return $this->render('tutoriel/new.html.twig', [
            'tutoriel' => $tutoriel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tutoriel_show', methods: ['GET'])]
    public function show(Tutoriel $tutoriel): Response
    {
        if ($this->isGranted('ROLE_BANNED')) {
            return $this->render('banned.html.twig');
        }

        return $this->render('tutoriel/show.html.twig', [
            'tutoriel' => $tutoriel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tutoriel_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Tutoriel $tutoriel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TutorielType::class, $tutoriel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tutoriel->setUpdatedAt(new \DateTime());
            $entityManager->flush();

            $this->addFlash('success', 'Le tutoriel a été modifié avec succès.');
            return $this->redirectToRoute('app_tutoriel_show', ['id' => $tutoriel->getId()]);
        }

        return $this->render('tutoriel/edit.html.twig', [
            'tutoriel' => $tutoriel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tutoriel_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Tutoriel $tutoriel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tutoriel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tutoriel);
            $entityManager->flush();
            $this->addFlash('success', 'Le tutoriel a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_tutoriel_index');
    }

    #[Route('/matiere/{id}', name: 'app_tutoriel_by_matiere', methods: ['GET'])]
    public function byMatiere(Matiere $matiere): Response
    {
        if ($this->isGranted('ROLE_BANNED')) {
            return $this->render('banned.html.twig');
        }

        return $this->render('tutoriel/by_matiere.html.twig', [
            'matiere' => $matiere,
            'tutoriels' => $matiere->getTutoriels(),
        ]);
    }
}