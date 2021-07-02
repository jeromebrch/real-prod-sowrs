<?php

namespace App\Repository;

use App\Entity\WorkAutorization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkAutorization|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkAutorization|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkAutorization[]    findAll()
 * @method WorkAutorization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkAutorizationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkAutorization::class);
    }

    // /**
    //  * @return WorkAutorization[] Returns an array of WorkAutorization objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkAutorization
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
