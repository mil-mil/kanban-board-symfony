<?php

namespace App\Repository;

use App\Entity\Column;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Column|null find($id, $lockMode = null, $lockVersion = null)
 * @method Column|null findOneBy(array $criteria, array $orderBy = null)
 * @method Column[]    findAll()
 * @method Column[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColumnRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Column::class);
    }

    /**
     * @return Column[] Returns an array of Card objects
     */
    public function findAllOrderedByPosition()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
