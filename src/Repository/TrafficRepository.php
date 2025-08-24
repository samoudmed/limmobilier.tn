<?php

namespace App\Repository;

use App\Entity\Traffic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Traffic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Traffic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Traffic[]    findAll()
 * @method Traffic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrafficRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Traffic::class);
    }

    // /**
    //  * @return Traffic[] Returns an array of Traffic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Traffic
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
