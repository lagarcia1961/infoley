<?php

namespace App\Controller;

use App\Entity\Norma;
use App\Repository\NormaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Attribute\IsGranted;

#[Route('/norma')]
class NormaController extends AbstractController
{
    #[Route('/', name: 'app_norma')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_home_front');
    }

    #[Route('/{slug}', name: 'app_show_norma')]
    public function show_norma($slug, NormaRepository $normaRepository): Response
    {
        $data['norma'] = $normaRepository->findOneBy(['slug' => $slug, 'isActive' => true]);
        if (!$data['norma']) {
            throw new NotFoundHttpException('La norma no fue encontrada.');
        }

        $data['normasDestino'] = $data['norma']->getNormasDestino()->toArray();
        $data['normasOrigen'] = $data['norma']->getNormasOrigen()->toArray();

        usort($data['normasDestino'], fn($a, $b) => $a->getNormaOrigen()->getFechaSancion() <=> $b->getNormaOrigen()->getFechaSancion());
        usort($data['normasOrigen'], fn($a, $b) => $a->getNormaDestino()->getFechaSancion() <=> $b->getNormaDestino()->getFechaSancion());


        return $this->render('norma/index.html.twig', $data);
    }
}
