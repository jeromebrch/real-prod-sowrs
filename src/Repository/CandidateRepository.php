<?php

namespace App\Repository;

use App\Data\SearchCandidate;
use App\Entity\Candidate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use mysql_xdevapi\Result;

/**
 * @method Candidate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidate[]    findAll()
 * @method Candidate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidate::class);
    }

    // /**
    //  * @return Candidate[] Returns an array of Candidate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * @param $id
     * @return Candidate|null
     */
    public function showOneCandidate($id): ?Candidate
    {
        $query = $this->createQueryBuilder('c')
            ->addSelect('cou')
            ->leftJoin('c.authorizedCountry', 'cou')
            ->addSelect('js')
            ->leftJoin('c.jobSearch', 'js')
            ->addSelect('pic')
            ->leftJoin('c.picture', 'pic')
            ->addSelect('scor')
            ->leftJoin('c.scoring', 'scor')
            ->addSelect('ls')
            ->leftJoin('c.levelStudy', 'ls')
            ->addSelect('le')
            ->leftJoin('c.levelExperience', 'le')
            ->addSelect('sn')
            ->leftJoin('c.socialNetwork', 'sn')
            ->andWhere('c.id = :val')
            ->setParameter('val', $id);

        try {
            return $query->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @param SearchCandidate $data
     * @return Candidate[]
     */
    public function searchCandidate(SearchCandidate $data): array
    {
        $query = $this->createQueryBuilder('c')
            ->addSelect('o')
            ->leftJoin('c.jobSearch', 'o')
            ->andWhere('c.private = :f')
            ->setParameter('f', false)
            ->andWhere("c.authorizedCountry != ''")
            ->andWhere("c.authorizedCountry IS NOT NULL")
            ->andWhere("o.contractType != ''")
            ->andWhere("o.contractType IS NOT NULL")
            ->andWhere("o.cause != ''")
            ->andWhere("o.cause IS NOT NULL")
            ->andWhere("o.jobTitle != ''")
            ->andWhere("o.jobTitle IS NOT NULL")
            ;
        if (!empty($data->txt)) {
            $query = $query
                ->andWhere('c.lastname LIKE :nom')
                ->setParameter('nom', "%$data->txt%");
        }
        if (!empty($data->localization)) {
            $query = $query
                ->andWhere('o.country = :c')
                ->setParameter('c', $data->localization);
        }
        if (!empty($data->remuneration)) {
            $query = $query
                ->andWhere('o.desuredRemuneration = :r')
                ->setParameter('r', $data->remuneration);
        }
        if (!empty($data->contractType)) {
            $query = $query
                ->andWhere('o.contractType = :ct')
                ->setParameter('ct', $data->contractType);
        }
        if (!empty($data->cause)) {
            $query = $query
                ->andWhere('o.cause = :ct')
                ->setParameter('ct', $data->contractType);
        }
        if (!empty($data->levelStudy)) {
            $query = $query
                ->andWhere('c.levelStudy = :ls')
                ->setParameter('ls', $data->levelStudy);
        }
        if (!empty($data->levelExp)) {
            $query = $query
                ->andWhere('c.levelExperience = :le')
                ->setParameter('le', $data->levelExp);
        }
        if (!empty($data->authorizedCountry)) {
            $query = $query
                ->andWhere('c.authorizedCountry = :ac')
                ->setParameter('ac', $data->authorizedCountry);
        }

        return $query->getQuery()->getResult();

    }
}
