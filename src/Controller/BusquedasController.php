<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BusquedasController extends AbstractController
{
    #[Route('/busquedas', name: 'app_busquedas_norma')]
    public function normas(): Response
    {
        return $this->render('busquedas/index.html.twig', [
            'controller_name' => 'BusquedasController',
        ]);
    }
}
