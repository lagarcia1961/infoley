<?php

namespace App\Controller\Secure;

use App\Entity\Dependencia;
use App\Form\DependenciaType;
use App\Repository\DependenciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/secure/dependencia')]
class DependenciaController extends AbstractController

{
    #[Route('/', name: 'app_dependencia_index', methods: ['GET'])]
    public function index(DependenciaRepository $dependenciaRepository): Response
    {
        $dependenciasActivas = $dependenciaRepository->findBy(['isActive' => 1]);
    
        return $this->render('secure/dependencia/index.html.twig', [
            'dependencias' => $dependenciasActivas,
        ]);
    }

    #[Route('/new', name: 'app_dependencia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dependencia = new Dependencia();
        $form = $this->createForm(DependenciaType::class, $dependencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dependencia);
            $entityManager->flush();

            return $this->redirectToRoute('app_dependencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secure/dependencia/new.html.twig', [
            'dependencia' => $dependencia,
            'form' => $form,
        ]);
    }

     #[Route('/{id}/edit', name: 'app_dependencia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dependencia $dependencia, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DependenciaType::class, $dependencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dependencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secure/dependencia/edit.html.twig', [
            'dependencia' => $dependencia,
            'form' => $form,
        ]);
    }

 
    #[Route('/eliminar', name: 'app_dependencia_delete', methods: ['POST'])]
    public function eliminar(DependenciaRepository $dependenciaRepository, Request $request, EntityManagerInterface $em): JsonResponse
    { {
            // Obtener el ID desde el cuerpo de la solicitud
            $id = $request->request->get('id') ?? null;

            // Verificar si se proporcionó un ID
            if (!$id) {
                return new JsonResponse(['success' => false, 'message' => 'ID no proporcionado.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Buscar el dependencia por ID
            $dependencia = $dependenciaRepository->findOneBy(['id' => $id, 'isActive' => true]);

            // Verificar si el dependencia existe
            if (!$dependencia) {
                return new JsonResponse(['success' => false, 'message' => 'Dependencia no encontrada.'], JsonResponse::HTTP_NOT_FOUND);
            }

            try {
                // Eliminar el Dependencia
                $dependencia->setActive(false);
                $em->persist($dependencia);
                $em->flush();

                return new JsonResponse(['success' => true, 'message' => 'Dependencia eliminada con éxito.', 'title' => 'Eliminada!']);
            } catch (\Exception $e) {
                // Manejo de errores
                return new JsonResponse(['success' => false, 'message' => 'Error al eliminar la Dependencia.', 'title' => 'Error!'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }        



}

