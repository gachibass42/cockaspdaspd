<?php

namespace App\Repository;

use App\Entity\TripUserRole;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TripUserRole>
 *
 * @method TripUserRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method TripUserRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method TripUserRole[]    findAll()
 * @method TripUserRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripUserRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TripUserRole::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TripUserRole $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(TripUserRole $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function save(TripUserRole $tripUserRole) {
        $this->_em->persist($tripUserRole);
    }

    public function removeByTrip(string $trip) {

        $this->createQueryBuilder('trip_user_role')
            ->delete(TripUserRole::class,'trip_user_role')
            ->where('trip_user_role.trip = :tripID')
            ->setParameter('tripID',$trip)
            ->getQuery()
            ->execute();

        //->andWhere('a.exampleField = :val')

    }

    public function removeByUser (User $user) {
        $this->createQueryBuilder('trip_user_role')
            ->delete(TripUserRole::class,'trip_user_role')
            ->where('trip_user_role.tripUser = :user')
            ->setParameter('user',$user)
            ->getQuery()
            ->execute();
    }
    // /**
    //  * @return TripUserRole[] Returns an array of TripUserRole objects
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
    public function findOneBySomeField($value): ?TripUserRole
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
