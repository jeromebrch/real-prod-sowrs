<?php

namespace App\Repository;

use App\Entity\Cause;
use App\Entity\Recruiter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cause|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cause|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cause[]    findAll()
 * @method Cause[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CauseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cause::class);
    }


    public function exclusionCauses(Recruiter $user) // todo : à revoir !!!
    {
        //DQL
       $qb = $this->getEntityManager()->createQuery('SELECT a FROM App\Entity\Cause a'
           . ' WHERE a.id NOT IN (SELECT a2.id FROM App\Entity\Recognition ar '
           . ' JOIN App\Entity\Cause a2 WHERE ar.cause = a2 AND ar.recruiter = '
           . $user->getId() . ')');

        return $qb->getResult();
    }

}