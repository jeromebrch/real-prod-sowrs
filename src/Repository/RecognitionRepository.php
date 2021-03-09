<?php

namespace App\Repository;

use App\Entity\Recognition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recognition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recognition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recognition[]    findAll()
 * @method Recognition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecognitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recognition::class);
    }

    // /**
    //  * @return Recognition[] Returns an array of Recognition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recognition
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
