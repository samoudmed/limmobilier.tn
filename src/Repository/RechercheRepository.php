<?php

namespace App\Repository;

use App\Entity\Recherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recherche|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recherche|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recherche[]    findAll()
 * @method Recherche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RechercheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recherche::class);
    }

    // /**
    //  * @return Recherche[] Returns an array of Recherche objects
    //  */
    public function countByOffre()
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id) as nbr, r.offre')     
            ->andWhere("r.offre != ''")
            ->andWhere("r.offre != 'NULL'")
            ->andWhere("r.offre != 'undefined'")    
            ->groupBy('r.offre')    
            ->getQuery()
            ->getResult()
        ;
    }
    
    // /**
    //  * @return Recherche[] Returns an array of Recherche objects
    //  */
    public function countByVille()
    {
        return $this->createQueryBuilder('r')
            ->select('v.label, COUNT(r.id) as nbr')
            ->leftJoin('r.ville', 'v')           
            ->andWhere("r.ville != 'NULL'")    
            ->groupBy('r.ville') 
            ->orderBy('nbr', 'ASC')     
            ->getQuery()
                
            ->getResult()
        ;
    }
    
    // /**
    //  * @return Recherche[] Returns an array of Recherche objects
    //  */
    public function countByType()
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id) as nbr, t.label')
            ->join('r.type', 't')
            ->andWhere("r.type != 'NULL'")        
            ->groupBy('r.type')   
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Recherche
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
