<?php

namespace App\Repository;

use App\Entity\Seccion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Seccion>
 */
class SeccionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seccion::class);
    }

    public function findActiveSeccionesWithActiveNormas(): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.seccionNormas', 'sn')
            ->innerJoin('sn.norma', 'n')
            ->addSelect('sn', 'n')
            ->where('s.isActive = :isActive')
            ->andWhere('n.isActive = :normaIsActive')
            ->setParameter('isActive', true)
            ->setParameter('normaIsActive', true)
            ->orderBy('s.orden', 'ASC')
            ->addOrderBy('sn.orden', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Seccion[] Returns an array of Seccion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Seccion
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
