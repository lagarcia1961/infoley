<?php

namespace App\Controller\Secure;

use App\Entity\TipoNorma;
use App\Form\TipoNormaType;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/secure/tipo-norma')]
class TipoNormaController extends AbstractController
{
    #[Route('/', name: 'app_tipo_norma_index', methods: ['GET'])]
    public function index(TipoNormaRepository $tipoNormaRepository): Response
    {
        $tipoNormasActivas = $tipoNormaRepository->findBy(['isActive' => 1]);
    
        return $this->render('secure/tipo_norma/index.html.twig', [
            'tipo_normas' => $tipoNormasActivas,
        ]);
    }

    #[Route('/new', name: 'app_tipo_norma_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tipoNorma = new TipoNorma();
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

 
    #[Route('/eliminar', name: 'app_tipo_norma_delete', methods: ['POST'])]
    public function eliminar(TipoNormaRepository $tipoNormaRepository, Request $request, EntityManagerInterface $em): JsonResponse
    { 
            // Obtener el ID desde el cuerpo de la solicitud
            $id = $request->request->get('id') ?? null;

            // Verificar si se proporcionó un ID
            if (!$id) {
                return new JsonResponse(['success' => false, 'message' => 'ID no proporcionado.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Buscar el tipo de Norma por ID
            $tipoNorma = $tipoNormaRepository->findOneBy(['id' => $id, 'isActive' => true]);

            // Verificar si el tipo de norma existe
            if (!$tipoNorma) {
                return new JsonResponse(['success' => false, 'message' => 'Tipo de Norma no encontrada.'], JsonResponse::HTTP_NOT_FOUND);
            }

            try {
                // Eliminar el Tipo de norma
                $tipoNorma->setActive(false);
                $em->persist($tipoNorma);
                $em->flush();

                return new JsonResponse(['success' => true, 'message' => 'Tipo de Norma eliminada con éxito.', 'title' => 'Eliminada!']);
            } catch (\Exception $e) {
                // Manejo de errores
                return new JsonResponse(['success' => false, 'message' => 'Error al eliminar el Tipo de Norma.', 'title' => 'Error!'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
    }        



}
