<?php

namespace App\Repository;

use App\Entity\SeccionNorma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SeccionNorma>
 */
class SeccionNormaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeccionNorma::class);
    }

    public function findActiveNormasBySeccion(int $seccionId): array
    {
        return $this->createQueryBuilder('sn')
            ->innerJoin('sn.norma', 'n')
            ->addSelect('n')
            ->where('sn.seccion = :seccionId')
            ->andWhere('n.isActive = :isActive')
            ->setParameter('seccionId', $seccionId)
            ->setParameter('isActive', true)
            ->orderBy('sn.orden', 'ASC')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return SeccionNorma[] Returns an array of SeccionNorma objects
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

//    public function findOneBySomeField($value): ?SeccionNorma
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
