<?php

namespace App\Controller\Secure;

use App\Entity\Seccion;
use App\Entity\SeccionNorma;
use App\Form\SeccionNormaType;
use App\Form\SeccionType;
use App\Repository\SeccionNormaRepository;
use App\Repository\SeccionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/secure/secciones')]
class SeccionesController extends AbstractController
{
    #[Route('/', name: 'app_secure_secciones_index')]
    public function index(SeccionRepository $seccionRepository): Response
    {
        $data['secciones'] = $seccionRepository->findActiveSeccionesWithActiveTemas();
        return $this->render('secure/secciones/abm_secciones.html.twig', $data);
    }

    #[Route('/new', name: 'app_secure_seccion_new')]
    public function seccionNew(Request $request, EntityManagerInterface $entityManager, SeccionRepository $seccionRepository): Response
    {
        $data['seccion'] = new Seccion();
        $data['form_seccion'] = $this->createForm(SeccionType::class, $data['seccion']);
        $data['form_seccion']->handleRequest($request);

        if ($data['form_seccion']->isSubmitted() && $data['form_seccion']->isValid()) {
            $seccion = $seccionRepository->findOneBy(['tema' => $data['form_seccion']->get('tema')->getData()]);
            if ($seccion) {
                if (!$seccion->isActive()) {
                    $seccion->setActive(true);
                }
                $seccion->setOrden($data['seccion']->getOrden());
                $entityManager->persist($seccion);
                $entityManager->flush();
            } else {
                $entityManager->persist($data['seccion']);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_secure_secciones_index');
        }

        return $this->render('secure/secciones/new_seccion.html.twig', $data);
    }

    #[Route('/edit/{id}', name: 'app_secure_secciones_editar')]
    public function editarSeccion($id, Request $request, EntityManagerInterface $entityManager, SeccionRepository $seccionRepository): Response
    {
        $data['seccion'] = $seccionRepository->find($id);
        if (!$data['seccion']) {
            return $this->redirectToRoute('app_secure_secciones_index');
        }
        $data['form_seccion'] = $this->createForm(SeccionType::class, $data['seccion'], ['is_edit' => true]);
        $data['form_seccion']->handleRequest($request);

        if ($data['form_seccion']->isSubmitted() && $data['form_seccion']->isValid()) {
            $entityManager->persist($data['seccion']);
            $entityManager->flush();
            return $this->redirectToRoute('app_secure_secciones_index');
        }

        return $this->render('secure/secciones/edit_seccion.html.twig', $data);
    }

    #[Route('/{id}/norma', name: 'app_secure_secciones_norma')]
    public function seccionNorma($id, SeccionRepository $seccionRepository, SeccionNormaRepository $seccionNormaRepository): Response
    {
        $data['seccion'] = $seccionRepository->findOneBy(['id' => $id, 'isActive' => true]);
        if (!$data['seccion']) {
            return $this->redirectToRoute('app_secure_secciones_index');
        }
        $data['secciones'] = $seccionNormaRepository->findActiveNormasBySeccion($data['seccion']->getId());
        return $this->render('secure/secciones/abm_secciones_normas.html.twig', $data);
    }

    #[Route('/{id}/norma/new', name: 'app_secure_secciones_norma_new')]
    public function seccionNormaNew($id, SeccionRepository $seccionRepository, SeccionNormaRepository $seccionNormaRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $data['titulo'] = 'Cargar';
        $data['seccion'] = $seccionRepository->findOneBy(['id' => $id, 'isActive' => true]);
        if (!$data['seccion']) {
            return $this->redirectToRoute('app_secure_secciones_index');
        }

        $data['files_js'] = [
            'seccion_norma/seccion_norma.js'
        ];

        $data['seccion_norma'] = new SeccionNorma();
        $data['form_seccion_norma'] = $this->createForm(SeccionNormaType::class, $data['seccion_norma'], ['tema' => $data['seccion']->getTema()]);
        $data['form_seccion_norma']->handleRequest($request);

        if ($data['form_seccion_norma']->isSubmitted() && $data['form_seccion_norma']->isValid()) {
            $seccionNorma = $seccionNormaRepository->findOneBy(['seccion' => $data['seccion'], 'norma' => $data['form_seccion_norma']->get('norma')->getData()]);
            if ($seccionNorma) {
                if (!$seccionNorma->isActive()) {
                    $seccionNorma->setActive(true);
                }
                $seccionNorma->setOrden($data['seccion_norma']->getOrden());
                $seccionNorma->setTitulo($data['seccion_norma']->getTitulo() != '' ? $data['seccion_norma']->getTitulo() : $data['seccion_norma']->getNorma()->getTitulo());
                $entityManager->persist($seccionNorma);
                $entityManager->flush();
            } else {
                $data['seccion_norma']->setSeccion($data['seccion']);
                $data['seccion_norma']->setTitulo($data['seccion_norma']->getTitulo() != '' ? $data['seccion_norma']->getTitulo() : $data['seccion_norma']->getNorma()->getTitulo());
                $entityManager->persist($data['seccion_norma']);
                $entityManager->flush();
            }
            return $this->redirectToRoute('app_secure_secciones_norma', ['id' => $id]);
        }

        return $this->render('secure/secciones/new_seccion_norma.html.twig', $data);
    }

    #[Route('/{id}/norma/edit/{seccion_norma_id}', name: 'app_secure_secciones_norma_edit')]
    public function seccionNormaEdit($id, $seccion_norma_id, SeccionRepository $seccionRepository, SeccionNormaRepository $seccionNormaRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $data['titulo'] = 'Editar';
        $data['seccion'] = $seccionRepository->findOneBy(['id' => $id, 'isActive' => true]);
        if (!$data['seccion']) {
            return $this->redirectToRoute('app_secure_secciones_index');
        }
        $data['seccion_norma'] = $seccionNormaRepository->findOneBy(['id' => $seccion_norma_id, 'isActive' => true]);

        $data['files_js'] = [
            'seccion_norma/seccion_norma.js'
        ];
        $data['form_seccion_norma'] = $this->createForm(SeccionNormaType::class, $data['seccion_norma'], ['tema' => $data['seccion']->getTema(), 'is_edit' => true]);
        $data['form_seccion_norma']->handleRequest($request);
        if ($data['form_seccion_norma']->isSubmitted() && $data['form_seccion_norma']->isValid()) {

            $data['seccion_norma']->setSeccion($data['seccion']);
            $data['seccion_norma']->setTitulo($data['seccion_norma']->getTitulo() != '' ? $data['seccion_norma']->getTitulo() : $data['seccion_norma']->getNorma()->getTitulo());
            $entityManager->persist($data['seccion_norma']);
            $entityManager->flush();
            return $this->redirectToRoute('app_secure_secciones_norma', ['id' => $id]);
        }

        return $this->render('secure/secciones/new_seccion_norma.html.twig', $data);
    }

    #[Route('/eliminar', name: 'app_secure_secciones_delete', methods: ['POST'])]
    public function eliminar(SeccionRepository $seccionRepository, Request $request, EntityManagerInterface $em): JsonResponse
    { {
            // Obtener el ID desde el cuerpo de la solicitud
            $id = $request->request->get('id') ?? null;

            // Verificar si se proporcionó un ID
            if (!$id) {
                return new JsonResponse(['success' => false, 'message' => 'ID no proporcionado.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Buscar la norma por ID
            $seccion = $seccionRepository->findOneBy(['id' => $id, 'isActive' => true]);

            // Verificar si la seccion existe
            if (!$seccion) {
                return new JsonResponse(['success' => false, 'message' => 'Sección no encontrada.'], JsonResponse::HTTP_NOT_FOUND);
            }

            try {
                // Eliminar la norma
                $seccion->setActive(false);
                $em->persist($seccion);
                $em->flush();

                return new JsonResponse(['success' => true, 'message' => 'Sección eliminada con éxito.', 'title' => 'Eliminada!']);
            } catch (\Exception $e) {
                // Manejo de errores
                return new JsonResponse(['success' => false, 'message' => 'Error al eliminar la Sección.', 'title' => 'Error!'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    #[Route('/eliminar/seccion_norma', name: 'app_secure_seccion_norma_delete', methods: ['POST'])]
    public function eliminarSeccionNorma(SeccionNormaRepository $seccionNormaRepository, Request $request, EntityManagerInterface $em): JsonResponse
    { {
            // Obtener el ID desde el cuerpo de la solicitud
            $id = $request->request->get('id') ?? null;

            // Verificar si se proporcionó un ID
            if (!$id) {
                return new JsonResponse(['success' => false, 'message' => 'ID no proporcionado.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Buscar la seccion_norma por ID
            $seccion_norma = $seccionNormaRepository->findOneBy(['id' => $id, 'isActive' => true]);

            // Verificar si la seccion existe
            if (!$seccion_norma) {
                return new JsonResponse(['success' => false, 'message' => 'Sección no encontrada.'], JsonResponse::HTTP_NOT_FOUND);
            }

            try {
                // Eliminar la seccion_norma
                $seccion_norma->setActive(false);
                $em->persist($seccion_norma);
                $em->flush();

                return new JsonResponse(['success' => true, 'message' => 'Sección eliminada con éxito.', 'title' => 'Eliminada!']);
            } catch (\Exception $e) {
                // Manejo de errores
                return new JsonResponse(['success' => false, 'message' => 'Error al eliminar la Sección.', 'title' => 'Error!'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
}
