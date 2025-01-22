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

    public function findActiveSeccionesWithActiveTemas(): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.tema', 't')
            ->addSelect('t')
            ->where('s.isActive = :isActive')
            ->andWhere('t.isActive = :temaIsActive')
            ->setParameter('isActive', true)
            ->setParameter('temaIsActive', true)
            ->orderBy('s.orden', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveSeccionesWithActiveNormas(): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.seccionNormas', 'sn')
            ->innerJoin('sn.norma', 'n')
            ->innerJoin('s.tema', 't')
            ->addSelect('sn', 'n','t')
            ->where('s.isActive = :isActive')
            ->andWhere('t.isActive = :temaIsActive')
            ->andWhere('n.isActive = :normaIsActive')
            ->setParameter('isActive', true)
            ->setParameter('normaIsActive', true)
            ->setParameter('temaIsActive', true)
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
