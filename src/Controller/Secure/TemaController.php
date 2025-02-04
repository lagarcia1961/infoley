<?php

namespace App\Controller\Secure;

use App\Entity\Tema;
use App\Form\TemaType;
use App\Repository\NormaRepository;
use App\Repository\NormaTemaRepository;
use App\Repository\TemaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/secure/tema')]
class TemaController extends AbstractController

{
    #[Route('/', name: 'app_tema_index', methods: ['GET'])]
    public function index(TemaRepository $temaRepository): Response
    {
        $temasActivos = $temaRepository->findBy(['isActive' => 1]);

        return $this->render('secure/tema/index.html.twig', [
            'temas' => $temasActivos,
        ]);
    }

    #[Route('/{id}/normas', name: 'app_tema_normas', methods: ['GET'])]
    public function temaNormas($id, NormaRepository $normaRepository, TemaRepository $temaRepository): Response
    {
        $tema = $temaRepository->findOneBy(['id' => $id, 'isActive' => true]);
        if (!$tema) {
            return $this->redirectToRoute('app_tema_index', [], Response::HTTP_SEE_OTHER);
        }
        $normas = $normaRepository->findActiveNormasByTema($id);
        return $this->render('secure/tema/tema_normas_list.html.twig', [
            'normas' => $normas,
            'tema'=> $tema,
        ]);
    }

    #[Route('/new', name: 'app_tema_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tema = new Tema();
        $form = $this->createForm(TemaType::class, $tema);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tema);
            $entityManager->flush();

            return $this->redirectToRoute('app_tema_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secure/tema/new.html.twig', [
            'tema' => $tema,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tema_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tema $tema, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TemaType::class, $tema);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tema_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secure/tema/edit.html.twig', [
            'tema' => $tema,
            'form' => $form,
        ]);
    }


    #[Route('/eliminar', name: 'app_tema_delete', methods: ['POST'])]
    public function eliminar(TemaRepository $temaRepository, Request $request, EntityManagerInterface $em): JsonResponse
    { {
            // Obtener el ID desde el cuerpo de la solicitud
            $id = $request->request->get('id') ?? null;

            // Verificar si se proporcionó un ID
            if (!$id) {
                return new JsonResponse(['success' => false, 'message' => 'ID no proporcionado.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Buscar el tema por ID
            $tema = $temaRepository->findOneBy(['id' => $id, 'isActive' => true]);

            // Verificar si el tema existe
            if (!$tema) {
                return new JsonResponse(['success' => false, 'message' => 'Tema no encontrada.'], JsonResponse::HTTP_NOT_FOUND);
            }

            try {
                // Eliminar el Tema
                $tema->setActive(false);
                $em->persist($tema);
                $em->flush();

                return new JsonResponse(['success' => true, 'message' => 'Tema eliminado con éxito.', 'title' => 'Eliminado!']);
            } catch (\Exception $e) {
                // Manejo de errores
                return new JsonResponse(['success' => false, 'message' => 'Error al eliminar el Tema.', 'title' => 'Error!'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
}
