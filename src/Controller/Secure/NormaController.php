<?php

namespace App\Controller\Secure;

use App\Entity\Norma;
use App\Entity\NormaTema;
use App\Entity\Referencia;
use App\Form\NormaType;
use App\Repository\NormaRepository;
use App\Repository\TipoReferenciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function new(Request $request, EntityManagerInterface $entityManager, NormaRepository $normaRepository, TipoReferenciaRepository $tipoReferenciaRepository): Response
    {
        $data['norma'] = new Norma();
        $data['form_norma'] = $this->createForm(NormaType::class, $data['norma'], ['allow_extra_fields' => true]);
        $data['form_norma']->handleRequest($request);

        $data['files_js'] = [
            'normas/normas.js'
        ];
        if ($data['form_norma']->isSubmitted() && $data['form_norma']->isValid()) {
            $temas = $data['form_norma']->get('temas')->getData();
            $normasAgregadasOrigen = $data['form_norma']->getExtraData()['normasAgregadasOrigen'] ?? null;
            $normasAgregadasDestino = $data['form_norma']->getExtraData()['normasAgregadasDestino'] ?? null;
            if ($temas) {
                foreach ($temas as $tema) {
                    $normaTema = new NormaTema();
                    $normaTema->setNorma($data['norma']);
                    $normaTema->setTema($tema);
                    $entityManager->persist($normaTema);
                }
            }

            if ($normasAgregadasOrigen) {
                foreach ($normasAgregadasOrigen as $normaAgregadaOrigen) {
                    $referenciaOrigen = new Referencia();
                    $referenciaOrigen->setNormaDestino($data['norma']);
                    $referenciaOrigen->setNormaOrigen($normaRepository->find((int)$normaAgregadaOrigen['norma']));
                    $referenciaOrigen->setTipoReferencia($tipoReferenciaRepository->find((int)$normaAgregadaOrigen['tipoReferencia']));
                    $entityManager->persist($referenciaOrigen);
                }
            }
            if ($normasAgregadasDestino) {
                foreach ($normasAgregadasDestino as $normaAgregadaDestino) {
                    $referenciaDestino = new Referencia();
                    $referenciaDestino->setNormaOrigen($data['norma']);
                    $referenciaDestino->setNormaDestino($normaRepository->find((int)$normaAgregadaDestino['norma']));
                    $referenciaDestino->setTipoReferencia($tipoReferenciaRepository->find((int)$normaAgregadaDestino['tipoReferencia']));
                    $entityManager->persist($referenciaDestino);
                }
            }

            $file = $data['form_norma']->get('urlPdf')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                //$newFilename = uniqid().'.'.$file->guessExtension();
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    $pdfUploadsDir = $this->getParameter('pdf_uploads_directory');
                    $file->move($pdfUploadsDir, $newFilename);
                } catch (FileException $e) {
                    // Manejar error si algo falla en la subida
                }

                $data['norma']->setUrlPdf($newFilename);
            }
            $entityManager->persist($data['norma']);
            $entityManager->flush();

            return $this->redirectToRoute('app_norma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secure/norma/new.html.twig', $data);
    }


    #[Route('/{id}/edit', name: 'app_norma_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Norma $norma, EntityManagerInterface $entityManager, NormaRepository $normaRepository, TipoReferenciaRepository $tipoReferenciaRepository): Response
    {
        // Crear el formulario con la entidad Norma
        $form_norma = $this->createForm(NormaType::class, $norma, ['allow_extra_fields' => true]);
        // Obtener todas las instancias de UsuarioTipoNorma asociadas al usuario
        $normaTemas = $norma->getNormaTemas();
        $files_js = [
            'normas/normas.js'
        ];
        // Crear un array con los TipoNorma asociados
        $temas = array_map(function ($normaTema) {
            return $normaTema->getTema();
        }, $normaTemas->toArray());

        if ($temas) {
            $form_norma->get('temas')->setData($temas); // Establece las normas seleccionadas en el formulario
        }

        $form_norma->handleRequest($request);
        // Verificar si el formulario ha sido enviado y es válido
        if ($form_norma->isSubmitted() && $form_norma->isValid()) {
            // Obtener el archivo subido (si hay uno nuevo)
            $file = $form_norma->get('urlPdf')->getData();

            foreach ($norma->getNormaTemas() as $normaTema) {
                $entityManager->remove($normaTema);
            }

            foreach ($norma->getNormasDestino() as $normaOrigen) {
                $entityManager->remove($normaOrigen);
            }

            foreach ($norma->getNormasOrigen() as $normaOrigen) {
                $entityManager->remove($normaOrigen);
            }

            $temas = $form_norma->get('temas')->getData();
            $normasAgregadasOrigen = $form_norma->getExtraData()['normasAgregadasOrigen'] ?? null;
            $normasAgregadasDestino = $form_norma->getExtraData()['normasAgregadasDestino'] ?? null;


            if ($temas) {
                foreach ($temas as $tema) {
                    $normaTema = new NormaTema();
                    $normaTema->setNorma($norma);
                    $normaTema->setTema($tema);
                    $entityManager->persist($normaTema);
                }
            }

            if ($normasAgregadasOrigen) {
                foreach ($normasAgregadasOrigen as $normaAgregadaOrigen) {
                    $referenciaOrigen = new Referencia();
                    $referenciaOrigen->setNormaDestino($norma);
                    $referenciaOrigen->setNormaOrigen($normaRepository->find((int)$normaAgregadaOrigen['norma']));
                    $referenciaOrigen->setTipoReferencia($tipoReferenciaRepository->find((int)$normaAgregadaOrigen['tipoReferencia']));
                    $entityManager->persist($referenciaOrigen);
                }
            }
            if ($normasAgregadasDestino) {
                foreach ($normasAgregadasDestino as $normaAgregadaDestino) {
                    $referenciaDestino = new Referencia();
                    $referenciaDestino->setNormaOrigen($norma);
                    $referenciaDestino->setNormaDestino($normaRepository->find((int)$normaAgregadaDestino['norma']));
                    $referenciaDestino->setTipoReferencia($tipoReferenciaRepository->find((int)$normaAgregadaDestino['tipoReferencia']));
                    $entityManager->persist($referenciaDestino);
                }
            }


            // Si se sube un nuevo archivo, procesarlo
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // Crear un nombre único para el archivo subido
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    // Mover el archivo subido al directorio de almacenamiento
                    $pdfUploadsDir = $this->getParameter('pdf_uploads_directory');
                    $file->move($pdfUploadsDir, $newFilename);
                } catch (FileException $e) {
                    // Manejar el error si algo falla en la subida del archivo
                    $this->addFlash('error', 'Ocurrió un error al subir el archivo.');
                    return $this->redirectToRoute('app_norma_edit', ['id' => $norma->getId()]);
                }

                // Actualizar la entidad Norma con el nuevo nombre del archivo
                $norma->setUrlPdf($newFilename);
            } else {
                // Si no se sube un nuevo archivo, mantener el archivo actual
                $norma->setUrlPdf($norma->getUrlPdf());
            }

            // Guardar los cambios en la base de datos
            $entityManager->flush();

            // Redirigir a la lista de normas después de la edición exitosa
            return $this->redirectToRoute('app_norma_index', [], Response::HTTP_SEE_OTHER);
        }
        // Renderizar la vista del formulario de edición
        return $this->render('secure/norma/edit.html.twig', [
            'norma' => $norma,
            'files_js' => $files_js,
            'form_norma' => $form_norma,
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

    #[Route('/getNormas', name: 'app_get_normas', methods: ['POST'])]
    public function getNormas(NormaRepository $normaRepository, Request $request, EntityManagerInterface $em): JsonResponse
    { {
            $data = json_decode($request->getContent(), true);
            // Obtener el ID desde el cuerpo de la solicitud
            $tipoNormaId = $data['tipoNormaId'] ?? null;

            $menor = $data['menor'] ?? null;

            $normaId = $data['normaId'] ?? false;
            // Verificar si se proporcionó un ID
            if (!$tipoNormaId) {
                return new JsonResponse(['success' => false, 'message' => 'ID no proporcionado.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Buscar la norma por ID
            $normas = $normaRepository->findNormasByTipoNormaRango($tipoNormaId, $menor, $normaId);

            $normasData = [];
            foreach ($normas as $norma) {
                $normasData[] = $norma->getNormaFullData();
            }

            return new JsonResponse(['success' => true, 'normas' => $normasData]);
        }
    }
}
