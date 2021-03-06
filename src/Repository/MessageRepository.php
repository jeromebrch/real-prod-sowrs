<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /*
    * @return User
    *
    */
    public function findByUserRecipient(User $userRecipient)
    {
        return $this->createQueryBuilder('message')
            ->where('message.userRecipient = :user')
            ->setParameter(':user', $userRecipient)
            ->orderBy('message.createdAt','DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAppliesByJobOffer(Category $category)
    {
        return $this->createQueryBuilder('message')
            ->Where('message.category = candidature')
            ->setParameter('candidature', $category)
            ->getQuery()
            ->getResult();
    }




    public function findCategoryMessage($id)
    {
        return $this->createQueryBuilder('message')
            ->where('message.category.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUserRecipientAndCategory(User $userRecipient, $category)
    {
        return $this->createQueryBuilder('message')
            ->andWhere('message.category = :cat')
            ->setParameter('cat', $category)
            ->andWhere('message.userRecipient = :user')
            ->setParameter('user', $userRecipient)
            ->getQuery()
            ->getResult()
            ;
    }
}
