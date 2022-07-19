<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Modules\Syncer\Model\SyncObjectTrip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Object_;

/**
 * @extends ServiceEntityRepository<Trip>
 *
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Trip $entity, bool $flush = true): void
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
    public function remove(Trip $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function save (Trip $trip) {
        $this->getEntityManager()->persist($trip);
        //$this->_em->flush();
    }

    public function getObjectsForSync (\DateTime $lastSyncDate): array {
        $dbObjects = $this->createQueryBuilder('object')
            ->where('object.syncDate > :value')
            ->setParameter('value', $lastSyncDate)
            ->orderBy('object.objID', 'ASC')
            ->getQuery()
            ->getResult()
            ;
        return (array_map(fn(Trip $object) => new SyncObjectTrip(
            $object->getObjId(),
            $object->getSyncDate()->getTimestamp(),
            $object->getName(),
            $object->getOwner() ? $object->getOwner()->getId() : null,
            $object->getStartDate()->getTimestamp(),
            $object->getEndDate()->getTimestamp(),
            $object->getLocked(),
            $object->getDuration(),
            $object->getTripDescription(),
            $object->getTags(),
            null,//TODO: trip participants
            [$object->getMainImage()],
            $object->getMilestonesIDs(),
            $object->getVisibility()
        ), $dbObjects));
    }

    public function removeByID(string $objID) {
        /*$this->createQueryBuilder('trip')

        $qb = $this->createQueryBuilder('a');
        //->andWhere('a.exampleField = :val')
        for ($i = 1; $i < 40, !isset($result); $i = $i + 2) {
            $qb->where($qb->expr()->andX(
                $qb->expr()->lt($qb->expr()->abs($qb->expr()->diff('a.latitude',':lat')),$i*0.01),
                $qb->expr()->lt($qb->expr()->abs($qb->expr()->diff('a.longitude',':lon')),$i*0.01)
            ))
                ->setParameter('lat', $latitude)
                ->setParameter('lon', $longitude)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }*/
    }

    // /**
    //  * @return Trip[] Returns an array of Trip objects
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
    public function findOneBySomeField($value): ?Trip
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
