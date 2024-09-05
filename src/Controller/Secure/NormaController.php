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
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('secure/norma')]
class NormaController extends AbstractController
{
    #[Route('/', name: 'app_norma_index', methods: ['GET'])]
    public function index(NormaRepository $normaRepository): Response
    {
        $normasActivas = $normaRepository->findBy(['isActive' => 1]);
    
        return $this->render('secure/norma/abm_norma.html.twig', [
            'normas' => $normasActivas,
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

        return $this->render('secure/norma/new.html.twig', [
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

    #[Route('/eliminar', name: 'app_norma_delete', methods: ['POST'])]
    public function eliminar(NormaRepository $normaRepository, Request $request, EntityManagerInterface $em): JsonResponse
    { {
            // Obtener el ID desde el cuerpo de la solicitud
            $id = $request->request->get('id') ?? null;

            // Verificar si se proporcionó un ID
            if (!$id) {
                return new JsonResponse(['success' => false, 'message' => 'ID no proporcionado.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Buscar la norma por ID
            $norma = $normaRepository->findOneBy(['id' => $id, 'isActive' => true]);

            // Verificar si la norma existe
            if (!$norma) {
                return new JsonResponse(['success' => false, 'message' => 'Norma no encontrada.'], JsonResponse::HTTP_NOT_FOUND);
            }

            try {
                // Eliminar la norma
                $norma->setActive(false);
                $em->persist($norma);
                $em->flush();

                return new JsonResponse(['success' => true, 'message' => 'Norma eliminada con éxito.', 'title' => 'Eliminada!']);
            } catch (\Exception $e) {
                // Manejo de errores
                return new JsonResponse(['success' => false, 'message' => 'Error al eliminar la Norma.', 'title' => 'Error!'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }        

}
