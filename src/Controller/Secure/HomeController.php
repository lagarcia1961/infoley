<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/secure/home')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_secure_home')]
    public function index(): Response
    {
        $data['title'] = 'Dashboard';
        $data['files_js'] = ['dashboard.init.js'];
        return $this->render('secure/home/index.html.twig', $data);
    }
}
