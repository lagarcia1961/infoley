<?php

namespace App\Controller\Secure;

use App\Repository\AuditoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuditoriaController extends AbstractController
{
    #[Route('/secure/auditoria', name: 'app_secure_auditoria')]
    public function index(AuditoriaRepository $auditoriaRepository): Response
    {
        $data['auditorias'] = $auditoriaRepository->findBy([], ['fecha' => 'DESC']);
        return $this->render('secure/auditoria/index.html.twig', $data);
    }
}
