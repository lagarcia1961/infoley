<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/secure/home', name: 'app_secure_home')]
    public function index(): Response
    {
        return $this->render('secure/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
