<?php

namespace App\Repository;

use App\Entity\LevelExperience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LevelExperience|null find($id, $lockMode = null, $lockVersion = null)
 * @method LevelExperience|null findOneBy(array $criteria, array $orderBy = null)
 * @method LevelExperience[]    findAll()
 * @method LevelExperience[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LevelExperienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LevelExperience::class);
    }

    // /**
    //  * @return LevelExperience[] Returns an array of LevelExperience objects
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
    public function findOneBySomeField($value): ?LevelExperience
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
