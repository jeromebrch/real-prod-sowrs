<?php

namespace App\Repository;

use App\Entity\LevelStudy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LevelStudy|null find($id, $lockMode = null, $lockVersion = null)
 * @method LevelStudy|null findOneBy(array $criteria, array $orderBy = null)
 * @method LevelStudy[]    findAll()
 * @method LevelStudy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LevelStudyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LevelStudy::class);
    }

    // /**
    //  * @return LevelStudy[] Returns an array of LevelStudy objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LevelStudy
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
