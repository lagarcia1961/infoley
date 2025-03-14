<?php

namespace App\Controller\Secure;

use App\Entity\TipoReferencia;
use App\Form\TipoReferenciaType;
use App\Repository\TipoReferenciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/secure/tipo-referencia')]
class TipoReferenciaController extends AbstractController
{
    #[Route('/', name: 'app_tipo_referencia_index', methods: ['GET'])]
    public function index(TipoReferenciaRepository $tipoReferenciaRepository): Response
    {
        $tipoReferenciasActivas = $tipoReferenciaRepository->findBy(['isActive' => 1]);
    
        return $this->render('secure/tipo_referencia/index.html.twig', [
            'tipo_referencias' => $tipoReferenciasActivas,
        ]);
    }

    #[Route('/new', name: 'app_tipo_referencia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tipoReferencia = new TipoReferencia();
        $form = $this->createForm(TipoReferenciaType::class, $tipoReferencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tipoReferencia);
            $entityManager->flush();

            return $this->redirectToRoute('app_tipo_referencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secure/tipo_referencia/new.html.twig', [
            'tipo_referencia' => $tipoReferencia,
            'form' => $form,
        ]);
    }

     #[Route('/{id}/edit', name: 'app_tipo_referencia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TipoReferencia $tipoReferencia, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TipoReferenciaType::class, $tipoReferencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tipo_referencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secure/tipo_referencia/edit.html.twig', [
            'tipo_referencia' => $tipoReferencia,
            'form' => $form,
        ]);
    }

 
    #[Route('/eliminar', name: 'app_tipo_referencia_delete', methods: ['POST'])]
    public function eliminar(TipoReferenciaRepository $tipoReferenciaRepository, Request $request, EntityManagerInterface $em): JsonResponse
    { 
            // Obtener el ID desde el cuerpo de la solicitud
            $id = $request->request->get('id') ?? null;

            // Verificar si se proporcionó un ID
            if (!$id) {
                return new JsonResponse(['success' => false, 'message' => 'ID no proporcionado.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Buscar el tipo de Referencia por ID
            $tipoReferencia = $tipoReferenciaRepository->findOneBy(['id' => $id, 'isActive' => true]);

            // Verificar si el tipo de referencia existe
            if (!$tipoReferencia) {
                return new JsonResponse(['success' => false, 'message' => 'Tipo de Referencia no encontrada.'], JsonResponse::HTTP_NOT_FOUND);
            }

            try {
                // Eliminar el Tipo de referencia
                $tipoReferencia->setActive(false);
                $em->persist($tipoReferencia);
                $em->flush();

                return new JsonResponse(['success' => true, 'message' => 'Tipo de Referencia eliminada con éxito.', 'title' => 'Eliminada!']);
            } catch (\Exception $e) {
                // Manejo de errores
                return new JsonResponse(['success' => false, 'message' => 'Error al eliminar el Tipo de Referencia.', 'title' => 'Error!'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
    }        

}
