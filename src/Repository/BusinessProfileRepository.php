<?php

namespace App\Repository;

use App\Entity\BusinessProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BusinessProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusinessProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusinessProfile[]    findAll()
 * @method BusinessProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BusinessProfile::class);
    }

    // /**
    //  * @return BusinessProfile[] Returns an array of BusinessProfile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BusinessProfile
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
