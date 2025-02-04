<?php

namespace App\Controller;

use App\Form\BusquedaAvanzadaType;
use App\Form\BusquedaSimpleType;
use App\Repository\NormaRepository;
use App\Repository\SeccionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_front')]
    public function index(Request $request, NormaRepository $normaRepository, SeccionRepository $seccionRepository): Response
    {

        $data['form_simple'] = $this->createForm(BusquedaSimpleType::class);
        $data['form_avanzado'] = $this->createForm(BusquedaAvanzadaType::class);


        $data['form_simple']->handleRequest($request);
        $data['form_avanzado']->handleRequest($request);

        if ($data['form_avanzado']->isSubmitted()) {
            $data['form_avanzado_active'] = true;
        } else {
            $data['form_avanzado_active'] = false;
        }

        if ($data['form_simple']->isSubmitted() && $data['form_simple']->isValid()) {

            $tipoNorma = $data['form_simple']->get('tipoNorma')->getData();
            $numero = $data['form_simple']->get('numero')->getData();
            $anio = $data['form_simple']->get('anio')->getData();
            $data['normativas'] = $normaRepository->busquedaSimple($tipoNorma, $numero, $anio);
        }

        if ($data['form_avanzado']->isSubmitted() && $data['form_avanzado']->isValid()) {

            $tipoNorma = $data['form_avanzado']->get('tipoNorma')->getData();
            $numero = $data['form_avanzado']->get('numero')->getData();
            $anio = $data['form_avanzado']->get('anio')->getData();
            $texto = $data['form_avanzado']->get('texto')->getData();
            $dependencia = $data['form_avanzado']->get('dependencia')->getData();
            $tema = $data['form_avanzado']->get('tema')->getData();
            $fechaDesde = $data['form_avanzado']->get('fechaDesde')->getData();
            $fechaHasta = $data['form_avanzado']->get('fechaHasta')->getData();
            $data['normativas'] = $normaRepository->busquedaAvanzada($tipoNorma, $numero, $anio, $texto, $dependencia, $fechaDesde, $fechaHasta, $tema);
        }

        $data['secciones'] = $seccionRepository->findActiveSeccionesWithActiveNormas();

        return $this->render('home/index.html.twig', $data);
    }
}
