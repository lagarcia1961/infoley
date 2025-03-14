<?php

namespace App\Controller\Secure;

use App\Entity\Auditoria;
use App\Repository\AuditoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/secure/auditoria')]
class AuditoriaController extends AbstractController
{
    #[Route('/', name: 'app_secure_auditoria_index')]
    public function index(AuditoriaRepository $auditoriaRepository): Response
    {
        $data['auditorias'] = $auditoriaRepository->findBy([], ['fecha' => 'DESC']);
        return $this->render('secure/auditoria/index.html.twig', $data);
    }

    #[Route('/ver/{id}', name: 'app_secure_auditoria_ver', methods: ['GET'])]
    public function ver(Auditoria $auditoria): Response
    {
        $data['auditoria'] = $auditoria;
        return $this->render('secure/auditoria/ver.html.twig', $data);
    }

    #[Route('/historico', name: 'app_secure_auditoria_historico', methods: ['POST'])]
    public function historico(AuditoriaRepository $auditoriaRepository, Request $request): JsonResponse
    { 
            // Obtener el ID desde el cuerpo de la solicitud
            $id = $request->request->get('id') ?? null;
            $entidad = $request->request->get('entidad') ?? null;

            // Verificar si se proporcionó un ID
            if (!($id && $entidad)) {
                return new JsonResponse(['success' => false, 'message' => 'ID/Entidad no proporcionado.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Buscar la norma por ID
            $historicos = $auditoriaRepository->findBy(['entidad' => $entidad, 'entidadId' => $id], ['id' => 'DESC', 'fecha' => 'DESC']);

            $historicoArray = [];
            foreach ($historicos as $historico) {
                $historicoArray[] = $historico->getData();
            }

            // Verificar si la norma existe
            if (!$historicoArray) {
                return new JsonResponse(['success' => false, 'message' => 'Historico no encontrado.'], JsonResponse::HTTP_NOT_FOUND);
            }

            return new JsonResponse(['success' => true, 'data' => $historicoArray]);
    }
}
