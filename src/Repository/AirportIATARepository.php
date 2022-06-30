<?php

namespace App\Repository;

use App\Entity\AirportIATA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AirportIATA>
 *
 * @method AirportIATA|null find($id, $lockMode = null, $lockVersion = null)
 * @method AirportIATA|null findOneBy(array $criteria, array $orderBy = null)
 * @method AirportIATA[]    findAll()
 * @method AirportIATA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AirportIATARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AirportIATA::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AirportIATA $entity, bool $flush = true): void
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
    public function remove(AirportIATA $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return AirportIATA[] Returns an array of AirportIATA objects
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
    public function findOneBySomeField($value): ?AirportIATA
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
