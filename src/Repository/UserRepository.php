<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, User::class);
    }

    public function countNewSubscribersThisMonth(): int
    {
        $firstDayOfMonth = (new \DateTime('first day of this month'))->setTime(0,0,0);

        return (int) $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->andWhere('u.registredAt >= :firstDayOfMonth')
            ->setParameter('firstDayOfMonth', $firstDayOfMonth)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function add(User $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    public function findAgences() {
        
        $date = new \DateTime('now');
        return $this->createQueryBuilder('u')
                        ->select('u as agency, COUNT(a.id) as adsCount')
                        ->leftJoin('u.annonces', 'a')
                        ->andWhere('u.type = :type')  // only agencies
                        ->andWhere('a.published = 1')
                        ->andWhere('a.statut = 1')
                        ->andWhere('a.deleted = 0')
                        ->setParameter('type', '1')
                        ->groupBy('u.agence')
                        ->orderBy('adsCount', 'DESC')
                        ->getQuery()
                        ->getResult();
    }

    public function findTopAgenciesByAds(int $limit = 3): array {
        
        $date = new \DateTime('now');
        return $this->createQueryBuilder('u')
                        ->select('u as agency, COUNT(a.id) as adsCount')
                        ->leftJoin('u.annonces', 'a')
                        ->andWhere('u.type = :type')  // only agencies
                        ->andWhere('a.published = 1')
                        ->andWhere('a.statut = 1')
                        ->andWhere('a.expired_at > :date')
                        ->andWhere('a.deleted = 0')
                        ->setParameter('type', '1')
                        ->setParameter('date', $date->format('Y-m-d'))
                        ->groupBy('u.agence')
                        ->orderBy('adsCount', 'DESC')
                        ->setMaxResults($limit)
                        ->getQuery()
                        ->getResult();
    }

}
