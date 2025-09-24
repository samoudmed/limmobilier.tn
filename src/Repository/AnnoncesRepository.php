<?php

// src/App/Repository/AnnoncesRepository.php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Annonces;
use App\Entity\Types;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class AnnoncesRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonces::class);
    }

    public function filter($filters) {

        $query = $this->createQueryBuilder('r')
                ->select('r');

        if (isset($filters['statut'])) {
            $query->andWhere('r.statut = :statut')
                    ->setParameter(':statut', $filters['statut']);
        }

        $query->orderBy('r.id', 'DESC');

        return $query->getQuery()->getResult();
    }

    public function findAll() {

        return $this->createQueryBuilder('a')
                        ->orderBy('a.id', 'DESC')
                        ->getQuery()->getResult();
    }

    public function findByBien($offre) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->where('a.offre = :offre')
                ->setParameter('offre', $offre)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function findByAgence($id) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->where('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.expired_at > :date')
                ->andWhere('a.deleted = 0')
                ->setParameter('date', $date->format('Y-m-d'))
                ->andWhere('a.user = :id')
                ->setParameter('id', $id)
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function findByBienType($offre, $type) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->where('a.kind = :type')
                ->setParameter('type', $type)
                ->andWhere('a.offre = :offre')
                ->setParameter('offre', $offre)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function findForStatistique() {

        $annonce = $this->createQueryBuilder('a')
                ->where('a.kind = 1')
                ->orWhere('a.kind = 2')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function findActive($limit) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->select('a')
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->setMaxResults($limit)
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }
    
    public function findOldAdsQuery()
    {
        $date = new \DateTime('now');

        return $this->createQueryBuilder('a')
            ->andWhere('a.published = 1')
            ->andWhere('a.statut = 1')
            ->andWhere('a.deleted = 0')
            ->andWhere('a.expired_at < :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->orderBy('a.id', 'DESC')
            ->getQuery();
    }

    public function findByBienVille($type_offre, $ville) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->join('a.ville', 'v')
                ->where('a.offre = :offre')
                ->setParameter('offre', $type_offre)
                ->andWhere('v.label = :ville')
                ->setParameter('ville', $ville)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function findByBienTypeVille($type_offre, $type_bien, $idville) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->join('a.ville', 'v')
                ->where('a.kind = :type')
                ->setParameter('type', $type_bien)
                ->andWhere('a.offre = :offre')
                ->setParameter('offre', $type_offre)
                ->andWhere('v.id = :idville')
                ->setParameter('idville', $idville)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function findByBienTypeDelegation($type_offre, $type_bien, $iddelegation) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->where('a.kind = :type')
                ->setParameter('type', $type_bien)
                ->andWhere('a.offre = :offre')
                ->setParameter('offre', $type_offre)
                ->andWhere('a.delegation = :iddelegation')
                ->setParameter('iddelegation', $iddelegation)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function findByBienTypeGouvernorat($type_offre, $type_bien, $gouvernorat) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->where('a.kind = :type')
                ->setParameter('type', $type_bien)
                ->andWhere('a.offre = :offre')
                ->setParameter('offre', $type_offre)
                ->andWhere('a.gouvernorat = :gouvernorat')
                ->setParameter('gouvernorat', $gouvernorat)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function findByVille($ville) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->join('a.ville', 'v')
                ->join('a.user', 'u')
                ->Where('v.id = :ville')
                ->setParameter('ville', $ville)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function findByDelegation($idDelegation) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->Where('a.delegation = :delegation')
                ->setParameter('delegation', $idDelegation)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function findByGouvernorat($idGouvernorat) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->Where('a.gouvernorat = :gouvernorat')
                ->setParameter('gouvernorat', $idGouvernorat)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function search($offre = null, $type = null, $ville = null) {

        $em = $this->getDoctrine()->getManager();
        if ($offre != null) {
            $query .= 'AND a.offre = :offre';
            $statement->bindValue('offre', $offre);
        }

        if ($type != null) {
            $join .= ' INNER JOIN types t ON a.kind_id = t.id ';
            $query .= 'AND t.label = :type';
            $statement->bindValue('type', $type);
        }

        $query = 'SELECT * FROM annonces a ' . $join . '  WHERE a.deleted = 0 and a.published = 1 and a.statut = 1 a.expired_at > "' . date('Y-m-d') . '"';
        $statement = $em->getConnection()->prepare($query);
        // Set parameters 

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
    }

    public function searchSimilar($offre, $type, $ville, $limit, $except) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->join('a.kind', 't')
                ->join('a.ville', 'v')
                ->Where('a.offre = :offre')
                ->setParameter('offre', $offre)
                ->andWhere('a.kind = :type')
                ->setParameter('type', $type)
                ->andWhere('v.id = :ville')
                ->setParameter('ville', $ville)
                ->andWhere('a.id != :except')
                ->setParameter('except', $except)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->setMaxResults($limit)
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function searchRandom($offre, $type, $limit) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->join('a.kind', 't')
                ->join('a.ville', 'v')
                ->Where('a.offre = :offre')
                ->setParameter('offre', $offre)
                ->andWhere('a.kind = :type')
                ->setParameter('type', $type)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->setMaxResults($limit)
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function searchSimilarCity($ville, $limit, $except) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->join('a.kind', 't')
                ->join('a.ville', 'v')
                ->where('v.id = :ville')
                ->setParameter('ville', $ville)
                ->andWhere('a.id != :except')
                ->setParameter('except', $except)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->setMaxResults($limit)
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function newsletterGeo($offre, $type, $ville, $gouvernorat, $delegation) {

        $date = new \DateTime('now');
        $annonce = $this->createQueryBuilder('a')
                ->Where('a.offre = :offre')
                ->setParameter('offre', $offre)
                ->andWhere('a.kind = :type')
                ->setParameter('type', $type)
                ->andWhere('a.ville = :ville')
                ->setParameter('ville', $ville)
                ->andWhere('a.gouvernorat = :gouvernorat')
                ->setParameter('gouvernorat', $gouvernorat)
                ->andWhere('a.delegation = :delegation')
                ->setParameter('delegation', $delegation)
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->setMaxResults(10)
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function getOldAds() {
        $date = new \DateTime('now');
        $date->modify('-2 years'); // Subtract 2 years from the current date

        $annonce = $this->createQueryBuilder('a')
                ->where('a.expired_at <= :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->andWhere('a.archived != 1')
                //->setMaxResults(100)
                ->orderBy('a.id', 'ASC')
                ->getQuery()
                ->execute();

        return $annonce;
    }

    public function searchMyAds(User $user, string $key) {

        $qb = $this->createQueryBuilder('a');
        $qb->where('a.label LIKE :key')
                ->orWhere('a.description LIKE :key') // Assuming description is part of the same entity
                ->andWhere('a.user = :id')
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->setParameter('key', '%' . $key . '%')
                ->setParameter('id', $user->getId())
                ->orderBy('a.id', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function createIsSearchableQueryBuilder() {
        $date = new \DateTime('now');
        $qb = $this->createQueryBuilder('a')
                ->andWhere('a.published = 1')
                ->andWhere('a.statut = 1')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.expired_at > :date')
                ->setParameter('date', $date->format('Y-m-d'));

        return $qb;
    }

    public function findAllActive() {

        $date = new \DateTime('now');
        return (int) $this->createQueryBuilder('a')
                        ->select('COUNT(a.id)')
                        ->andWhere('a.published = 1')
                        ->andWhere('a.statut = 1')
                        ->andWhere('a.deleted = 0')
                        ->andWhere('a.expired_at > :date')
                        ->setParameter('date', $date->format('Y-m-d'))
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    public function findAllAds() {

        return (int) $this->createQueryBuilder('a')
                        ->select('COUNT(a.id)')
                        ->andWhere('a.published = 1')
                        ->andWhere('a.statut = 1')
                        ->andWhere('a.deleted = 0')
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    public function countAdsCreatedToday(): int {
        $today = new \DateTime('today');
        $tomorrow = (clone $today)->modify('+1 day');

        return (int) $this->createQueryBuilder('a')
                        ->select('COUNT(a.id)')
                        ->andWhere('a.created_at >= :today')
                        ->andWhere('a.created_at < :tomorrow')
                        ->andWhere('a.published = 1')
                        ->andWhere('a.statut = 1')
                        ->andWhere('a.deleted = 0')
                        ->setParameter('today', $today)
                        ->setParameter('tomorrow', $tomorrow)
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    public function countAdsCreatedThisMonth(): int {
        $firstDayOfMonth = new \DateTime('first day of this month 00:00:00');
        $firstDayNextMonth = (clone $firstDayOfMonth)->modify('+1 month');

        return (int) $this->createQueryBuilder('a')
                        ->select('COUNT(a.id)')
                        ->andWhere('a.created_at >= :firstDay')
                        ->andWhere('a.created_at < :firstDayNextMonth')
                        ->andWhere('a.published = 1')
                        ->andWhere('a.statut = 1')
                        ->andWhere('a.deleted = 0')
                        ->setParameter('firstDay', $firstDayOfMonth)
                        ->setParameter('firstDayNextMonth', $firstDayNextMonth)
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    public function countAdsCreatedLastMonth(): int {
        $firstDayOfMonth = new \DateTime('first day of last month 00:00:00');
        $firstDayNextMonth = (clone $firstDayOfMonth)->modify('+1 month');

        return (int) $this->createQueryBuilder('a')
                        ->select('COUNT(a.id)')
                        ->andWhere('a.created_at >= :firstDay')
                        ->andWhere('a.created_at < :firstDayNextMonth')
                        ->andWhere('a.published = 1')
                        ->andWhere('a.statut = 1')
                        ->andWhere('a.deleted = 0')
                        ->setParameter('firstDay', $firstDayOfMonth)
                        ->setParameter('firstDayNextMonth', $firstDayNextMonth)
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    public function findAnnonceOfDay(): array
    {
        $today = new \DateTime('today');

        return $this->createQueryBuilder('a')
            ->andWhere('a.published = 1')
            ->andWhere('a.statut = 1')
            ->andWhere('a.deleted = 0')
            ->andWhere('a.created_at >= :today')
            ->setParameter('today', $today->format('Y-m-d'))
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
