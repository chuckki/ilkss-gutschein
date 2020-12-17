<?php

namespace App\Repository;

use App\Entity\Gutschein;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gutschein|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gutschein|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gutschein[]    findAll()
 * @method Gutschein[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GutscheinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gutschein::class);
    }

    // /**
    //  * @return Gutschein[] Returns an array of Gutschein objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gutschein
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
