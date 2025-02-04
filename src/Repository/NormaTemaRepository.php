<?php

namespace App\Repository;

use App\Entity\NormaTema;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NormaTema>
 */
class NormaTemaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NormaTema::class);
    }

    public function findActiveNormasByTema(int $temaId): array
    {
        return $this->createQueryBuilder('tn')
            ->innerJoin('tn.norma', 'n')
            ->addSelect('n')
            ->innerJoin('tn.tema', 't')
            ->addSelect('t')
            ->where('tn.tema = :temaId')
            ->andWhere('n.isActive = :isActiveNorma')
            ->andWhere('t.isActive = :isActiveTema')
            ->setParameter('temaId', $temaId)
            ->setParameter('isActiveNorma', true)
            ->setParameter('isActiveTema', true)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return NormaTema[] Returns an array of NormaTema objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?NormaTema
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
