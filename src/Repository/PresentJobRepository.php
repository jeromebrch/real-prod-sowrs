<?php

namespace App\Repository;

use App\Entity\PresentJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PresentJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method PresentJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method PresentJob[]    findAll()
 * @method PresentJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PresentJobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PresentJob::class);
    }

    // /**
    //  * @return PresentJob[] Returns an array of PresentJob objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PresentJob
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
