<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/secure/tema')]
class TemaController extends AbstractController
{
    #[Route('/', name: 'app_tema_index')]
    public function index(): Response
    {
        return $this->render('secure/tema/index.html.twig', [
            'controller_name' => 'TemaController',
        ]);
    }
}
