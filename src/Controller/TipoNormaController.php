<?php

namespace App\Controller;

use App\Entity\TipoNorma;
use App\Form\TipoNormaType;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tipo/norma')]
class TipoNormaController extends AbstractController
{
    #[Route('/', name: 'app_tipo_norma_index', methods: ['GET'])]
    public function index(TipoNormaRepository $tipoNormaRepository): Response
    {
        $tipoNormasActivas = $tipoNormaRepository->findBy(['is_active' => 1]);
    
        return $this->render('secure/tipo_norma/index.html.twig', [
            'tipo_normas' => $tipoNormasActivas,
        ]);
    }

    #[Route('/new', name: 'app_tipo_norma_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tipoNorma = new TipoNorma();
        $tipoNorma->setIsActive(true);
        $form = $this->createForm(TipoNormaType::class, $tipoNorma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tipoNorma);
            $entityManager->flush();

            return $this->redirectToRoute('app_tipo_norma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secure/tipo_norma/new.html.twig', [
            'tipo_norma' => $tipoNorma,
            'form' => $form,
        ]);
    }

     #[Route('/{id}/edit', name: 'app_tipo_norma_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TipoNorma $tipoNorma, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TipoNormaType::class, $tipoNorma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tipo_norma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secure/tipo_norma/edit.html.twig', [
            'tipo_norma' => $tipoNorma,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tipo_norma_delete', methods: ['POST'])]
    public function delete(Request $request, TipoNorma $tipoNorma, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoNorma->getId(), $request->request->get('_token'))) {
            $tipoNorma->setIsActive(false);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_tipo_norma_index', [], Response::HTTP_SEE_OTHER);
    }
        



}
