<?php

namespace App\Controller;

use App\Entity\Norma;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/norma')]
class NormaController extends AbstractController
{
    #[Route('/', name: 'app_norma')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_home_front');
    }

    #[Route('/{id}', name: 'app_show_norma')]
    public function show_norma(Norma $norma): Response
    {
        dd($norma);

        return $this->render('home/index.html.twig', $data);
    }
}
