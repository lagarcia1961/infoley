<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NormasController extends AbstractController
{
    #[Route('/secure/normas', name: 'app_secure_normas')]
    public function index(): Response
    {
        return $this->render('secure/normas/index.html.twig', [
            'controller_name' => 'NormasController',
        ]);
    }
}
