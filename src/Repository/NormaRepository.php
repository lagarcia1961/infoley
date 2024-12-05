<?php

namespace App\Repository;

use App\Entity\Norma;
use App\Entity\TipoNorma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Norma>
 */
class NormaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Norma::class);
    }

    /**
     * @return Norma[] Returns an array of Norma objects
     */
    public function findNormasByTipoNormaRango(int $tipoNormaId, bool $menor, int|bool $normaId): array
    {
        // Primero obtenemos el rango del tipo de norma dado el ID
        $tipoNorma = $this->getEntityManager()
            ->getRepository(TipoNorma::class)
            ->find($tipoNormaId);

        if (!$tipoNorma) {
            throw new \Exception('Tipo de Norma no encontrado');
        }

        $rango = $tipoNorma->getRango();  // Obtenemos el rango del tipo de norma

        // Creamos el QueryBuilder
        $qb = $this->createQueryBuilder('n')
            ->join('n.tipoNorma', 'tn')
            ->where('n.isActive = :isActive')
            ->setParameter('isActive', true);

        // Agregamos la condición según el parámetro $menor
        if ($menor) {
            $qb->andWhere('tn.rango <= :rango');
        } else {
            $qb->andWhere('tn.rango >= :rango');
        }
        if ($normaId) {
            $qb->andWhere('n.id != :normaId')
                ->setParameter('normaId', $normaId);
        }

        // Definimos el parámetro del rango y obtenemos el resultado
        return $qb->setParameter('rango', $rango)
            ->getQuery()
            ->getResult();
    }

    public function busquedaSimple($tipoNorma, $numero, $anio): array
    {
        $qb = $this->createQueryBuilder('n')
            ->join('n.tipoNorma', 'tn')
            ->where('n.isActive = :isActive')
            ->setParameter('isActive', true);

        if ($tipoNorma) {
            $qb->andWhere('n.tipoNorma = :tipoNorma')
                ->setParameter('tipoNorma', $tipoNorma);
        }

        if ($numero) {
            $qb->andWhere('n.numero = :numero')
                ->setParameter('numero', $numero);
        }

        if ($anio) {
            $qb->andWhere('YEAR(n.fechaSancion) = :anio')
                ->setParameter('anio', $anio);
        }

        return $qb->getQuery()->getResult();
    }

    public function busquedaAvanzada($tipoNorma, $numero, $anio, $texto, $dependencia, $fechaDesde, $fechaHasta): array
    {
        $qb = $this->createQueryBuilder('n')
            ->join('n.tipoNorma', 'tn')
            ->where('n.isActive = :isActive')
            ->setParameter('isActive', true);  // Asumimos que siempre buscas normas activas.

        // Filtro por tipo de norma
        if ($tipoNorma) {
            $qb->andWhere('n.tipoNorma = :tipoNorma')
                ->setParameter('tipoNorma', $tipoNorma);
        }

        // Filtro por número de norma
        if ($numero) {
            $qb->andWhere('n.numero = :numero')
                ->setParameter('numero', $numero);
        }

        // Filtro por año de la norma
        if ($anio) {
            $qb->andWhere('n.fechaSancion = :anio')
                ->setParameter('anio', $anio);
        }

        // Filtro por texto en el campo "textoCompleto"
        if ($texto) {
            $qb->andWhere('n.textoCompleto LIKE :texto')
                ->setParameter('texto', '%' . $texto . '%');  // Búsqueda parcial en cualquier parte del texto
        }

        // Filtro por dependencia
        if ($dependencia) {
            $qb->andWhere('n.dependencia = :dependencia')
                ->setParameter('dependencia', $dependencia);
        }

        // Filtro por fecha de publicación (desde)
        if ($fechaDesde) {
            $qb->andWhere('n.fechaPublicacion >= :fechaDesde')
                ->setParameter('fechaDesde', $fechaDesde);
        }

        // Filtro por fecha de publicación (hasta)
        if ($fechaHasta) {
            $qb->andWhere('n.fechaPublicacion <= :fechaHasta')
                ->setParameter('fechaHasta', $fechaHasta);
        }

        // Retornar los resultados
        return $qb->getQuery()
            ->getResult();
    }
}
