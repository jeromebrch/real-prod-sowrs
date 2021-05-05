<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * return the unpublished post
     */
    public function findUnpublishedPost(){
        return $this->createQueryBuilder('p')
            ->andWhere('p.isPublished = false')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * return the published post
     */
    public function findPublishedPost(){
        return $this->createQueryBuilder('p')
            ->andWhere('p.isPublished = true')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * return the three last post
     */
    public function findLatestPost(){
        return $this->createQueryBuilder('p')
            ->andWhere('p.isPublished = true')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * return the three most views post
     */
    public function findMoreViewsPost(){
        return $this->createQueryBuilder('p')
            ->andWhere('p.isPublished = true')
            ->orderBy('p.numberOfViews', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * return post collection from a tag
     */
    public function findPostsFromTag($tag){
        return $this->createQueryBuilder('p')
            ->andWhere(':valueTag MEMBER OF p.tags')
            ->setParameter('valueTag', $tag)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
