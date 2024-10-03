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

    //    public function findOneBySomeField($value): ?Norma
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
