<?php

namespace App\Repository;

use App\Data\SearchJobOffers;
use App\Entity\JobOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobOffer[]    findAll()
 * @method JobOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobOffer::class);
    }

    /**
     * Récupère les offres d'emploi d'un recruteur
     * @param $recruiterId
     * @return int|mixed|string
     */
    public function findJobOffersByRecruiterId($recruiterId)
    {
        return $this->createQueryBuilder('j')
            ->addSelect('e')
            ->join('j.entity', 'e')
            ->andWhere('e.id = :val')
            ->setParameter('val', $recruiterId)
            ->addOrderBy('j.creationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }


    /**
     * Récupère les offre emploi actives en lien avec une recherche
     * @param SearchJobOffers $search
     * @return JobOffer[]
     */
    public function SearchJobOffers(SearchJobOffers $search): array
    {
        $query = $this->createQueryBuilder('j')
            ->addSelect('r')
            ->join('j.entity', 'r')
            ->join('j.category', 'c')
            ->andWhere('j.published = :p')
            ->setParameter('p', true);

            if (!empty($search->q)){
                $arrayResearch = explode(" ", $search->q);
                for($i = 0;$i<count($arrayResearch);$i++){
                    $query = $query
                        ->andWhere('j.title LIKE :q OR c.wording LIKE :q')
                        ->setParameter('q', "%$arrayResearch[$i]%");
                }
            }
            if (!empty($search->country)){
                $query = $query
                    ->andWhere('j.country = :coun')
                    ->setParameter('coun', $search->country);
            }
            if(!empty($search->department)){
                $query = $query
                    ->andWhere('j.department = :dep')
                    ->setParameter('dep', $search->department);
            }
            if (!empty($search->contractType)){
                $query = $query
                    ->andWhere('j.contractType = :con')
                    ->setParameter('con', $search->contractType);
            }
            if (!empty($search->cause)){
                $query = $query
                    ->andWhere('r.mainCause = :cau')
                    ->setParameter('cau', $search->cause);
            }
            if (!empty($search->remuneration)){
                $query = $query
                    ->andWhere('j.remuneration = :rem')
                    ->setParameter('rem', $search->remuneration);
            }
            if (!empty($search->levelExperience)){
                $query = $query
                    ->andWhere('j.levelExperience = :levExp')
                    ->setParameter('levExp', $search->levelExperience);
            }
            if (!empty($search->levelStudy)){
                $query = $query
                    ->andWhere('j.levelStudy = :levStu')
                    ->setParameter('levStu', $search->levelStudy);
            }
            if ($search->telecommute){
                $query = $query
                    ->andWhere('j.telecommuting = :val')
                    ->setParameter('val', true);
            }
            if ($search->freshness){
                $query = $query
                    ->addOrderBy('j.creationDate', 'DESC');
            }
            if (!$search->freshness){
                $query = $query
                    ->addOrderBy('j.creationDate', 'DESC');
            }
            return $query->getQuery()->getResult();
    }
}
