<?php

namespace App\Controller;

use App\Form\BusquedaAvanzadaType;
use App\Form\BusquedaSimpleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_front')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
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

            $data['normativas'] = [
                (object)["titulo" => "Título A", "fechaPublicacion"=>"11/10/2024","descripcion"=>'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eum numquam ipsum dolorem ut! Culpa, recusandae ducimus. Incidunt aliquam eius rerum veniam. Ea, aspernatur odit accusantium quam error consequatur harum cumque.'],
                (object)["titulo" => "Título B", "fechaPublicacion"=>"12/10/2024","descripcion"=>'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eum numquam ipsum dolorem ut! Culpa, recusandae ducimus. Incidunt aliquam eius rerum veniam. Ea, aspernatur odit accusantium quam error consequatur harum cumque.'],
                (object)["titulo" => "Título C", "fechaPublicacion"=>"13/10/2024","descripcion"=>'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eum numquam ipsum dolorem ut! Culpa, recusandae ducimus. Incidunt aliquam eius rerum veniam. Ea, aspernatur odit accusantium quam error consequatur harum cumque.'],
                (object)["titulo" => "Título D", "fechaPublicacion"=>"14/10/2024","descripcion"=>'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eum numquam ipsum dolorem ut! Culpa, recusandae ducimus. Incidunt aliquam eius rerum veniam. Ea, aspernatur odit accusantium quam error consequatur harum cumque.'],
            ];
            $data['normativas'] = null;
        }

        if ($data['form_avanzado']->isSubmitted() && $data['form_avanzado']->isValid()) {

            $tipoNorma = $data['form_avanzado']->get('tipoNorma')->getData();
            $numero = $data['form_avanzado']->get('numero')->getData();
            $anio = $data['form_avanzado']->get('anio')->getData();
            $texto = $data['form_avanzado']->get('texto')->getData();
            $dependencia = $data['form_avanzado']->get('dependencia')->getData();
            $fechaDesde = $data['form_avanzado']->get('fechaDesde')->getData();
            $fechaHasta = $data['form_avanzado']->get('fechaHasta')->getData();

            // Normativa/Número
            // Fecha de publicación
            // Descripción
            $data['normativas'] = [
                (object)["titulo" => "Título A", "fechaPublicacion"=>"11/10/2024","descripcion"=>'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eum numquam ipsum dolorem ut! Culpa, recusandae ducimus. Incidunt aliquam eius rerum veniam. Ea, aspernatur odit accusantium quam error consequatur harum cumque.'],
                (object)["titulo" => "Título B", "fechaPublicacion"=>"12/10/2024","descripcion"=>'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eum numquam ipsum dolorem ut! Culpa, recusandae ducimus. Incidunt aliquam eius rerum veniam. Ea, aspernatur odit accusantium quam error consequatur harum cumque.'],
                (object)["titulo" => "Título C", "fechaPublicacion"=>"13/10/2024","descripcion"=>'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eum numquam ipsum dolorem ut! Culpa, recusandae ducimus. Incidunt aliquam eius rerum veniam. Ea, aspernatur odit accusantium quam error consequatur harum cumque.'],
                (object)["titulo" => "Título D", "fechaPublicacion"=>"14/10/2024","descripcion"=>'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eum numquam ipsum dolorem ut! Culpa, recusandae ducimus. Incidunt aliquam eius rerum veniam. Ea, aspernatur odit accusantium quam error consequatur harum cumque.'],
            ];
        }


        return $this->render('home/index.html.twig', $data);
    }
}
