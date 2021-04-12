<?php

namespace App\Repository;

use App\Entity\Cv;
use App\Entity\Favorite;
use App\Entity\JobOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Favorite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favorite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favorite[]    findAll()
 * @method Favorite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favorite::class);
    }

    /**
     * @param $cv
     * @return Favorite|null
     */
    public function findFavoriteCv(Cv $cv): ?Favorite
    {
        return $this->createQueryBuilder('favorite')
            ->Where('favorite.cv = cv')
            ->setParameter('cv', $cv)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param $offer
     * @return Favorite|null
     */
    public function findFavoriteOffer(JobOffer $offer): ?Favorite
    {
        return $this->createQueryBuilder('favorite')
            ->Where('favorite.jobOffer = offer')
            ->setParameter('offer', $offer)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }


}
