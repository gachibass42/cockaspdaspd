<?php

namespace App\Repository;

use App\Entity\Airline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Airline>
 *
 * @method Airline|null find($id, $lockMode = null, $lockVersion = null)
 * @method Airline|null findOneBy(array $criteria, array $orderBy = null)
 * @method Airline[]    findAll()
 * @method Airline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AirlineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Airline::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Airline $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Airline $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Airline $airline
     */
    public function updateAirline (Airline $airline): void
    {
        $this->getEntityManager()->persist($airline);
        $this->getEntityManager()->flush();

    }
    // /**
    //  * @return Airline[] Returns an array of Airline objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Airline
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
