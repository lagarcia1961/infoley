<?php

namespace App\Controller\Secure;

use App\Repository\DependenciaRepository;
use App\Repository\NormaRepository;
use App\Repository\SeccionRepository;
use App\Repository\TemaRepository;
use App\Repository\TipoNormaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/secure/home')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_secure_home')]
    public function index(SeccionRepository $seccionRepository, NormaRepository $normaRepository, TemaRepository $temaRepository, DependenciaRepository $dependenciaRepository, TipoNormaRepository $tipoNormaRepository): Response
    {
        $data['title'] = 'Dashboard';

        $data['normas_cargadas'] = $normaRepository->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->where('n.isActive = :isActive')
            ->setParameter('isActive', true)
            ->getQuery()
            ->getSingleScalarResult();

        $data['temas_cargados'] = $temaRepository->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.isActive = :isActive')
            ->setParameter('isActive', true)
            ->getQuery()
            ->getSingleScalarResult();

        $data['dependencias_cargadas'] = $dependenciaRepository->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->where('d.isActive = :isActive')
            ->setParameter('isActive', true)
            ->getQuery()
            ->getSingleScalarResult();

        $data['tipo_normas_cargadas'] = $tipoNormaRepository->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.isActive = :isActive')
            ->setParameter('isActive', true)
            ->getQuery()
            ->getSingleScalarResult();

        $data['normas_por_tema'] = $temaRepository->createQueryBuilder('t')
            ->select('t.nombre as tema, COUNT(nt.id) as total')
            ->leftJoin('t.normaTemas', 'nt')
            ->leftJoin('nt.norma', 'n') // Asegúrate de que las normas también sean activas
            ->where('t.isActive = true')
            ->andWhere('n.isActive = true') // Filtra solo normas activas
            ->groupBy('t.id')
            ->orderBy('total', 'DESC') // Ordena por cantidad en forma descendente
            ->getQuery()
            ->getResult();

        $data['normas_por_tipo'] = $tipoNormaRepository->createQueryBuilder('t')
            ->select('t.nombre AS tipo, COUNT(n.id) AS total')
            ->leftJoin('t.normas', 'n')
            ->where('t.isActive = true') // Filtra tipos de norma activos
            ->andWhere('n.isActive = true') // Filtra normas activas
            ->groupBy('t.id')
            ->orderBy('total', 'DESC') // Ordena por cantidad en forma descendente
            ->getQuery()
            ->getResult();

        $data['secciones'] = $seccionRepository->findBy(['isActive' => true], ['orden' => 'ASC']);

        return $this->render('secure/home/index.html.twig', $data);
    }
}
