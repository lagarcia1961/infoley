<?php

namespace App\Controller\Secure;

use App\Entity\Norma;
use App\Form\NormaType;
use App\Repository\NormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('secure/norma')]
class NormaController extends AbstractController
{
    #[Route('/', name: 'app_norma_index', methods: ['GET'])]
    public function index(NormaRepository $normaRepository): Response
    {
        return $this->render('secure/norma/abm_norma.html.twig', [
            'normas' => $normaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_norma_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $norma = new Norma();
        $form = $this->createForm(NormaType::class, $norma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($norma);
            $entityManager->flush();

            return $this->redirectToRoute('app_norma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secure/norma/form_norma.html.twig', [
            'norma' => $norma,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_norma_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Norma $norma, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NormaType::class, $norma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_norma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secure/norma/edit.html.twig', [
            'norma' => $norma,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_norma_delete', methods: ['POST'])]
    public function delete(Request $request, Norma $norma, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$norma->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($norma);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_norma_index', [], Response::HTTP_SEE_OTHER);
    }
}
