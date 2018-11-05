<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Card::class);
    }

   /**
    * @return Card[] Returns an array of Card objects
    */
    public function findAllOrderedByPosition($columnId)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.inColumn = :columnId')
            ->setParameter('columnId', $columnId)
            ->orderBy('c.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
