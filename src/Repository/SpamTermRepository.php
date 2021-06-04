<?php

namespace App\Repository;

use App\Entity\SpamTerm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpamTerm|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpamTerm|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpamTerm[]    findAll()
 * @method SpamTerm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpamTermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpamTerm::class);
    }

    // /**
    //  * @return SpamTerm[] Returns an array of SpamTerm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpamTerm
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
