<?php

namespace App\Repository;

use App\Entity\Milestone;
use App\Entity\Trip;
use App\Modules\Syncer\Model\SyncObjectTrip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\String_;

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
     */
    public function add(Trip $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
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

    /**
     * @param \DateTime $lastSyncDate
     * @return SyncObjectTrip[]
     */
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
            $object->getUsersRoles()->getValues(),
            $object->getMainImage(),
            $object->getMilestonesIDs(),
            $object->getVisibility(),
            $object->getCheckListsIDs(),
            $object->getCost(),
            $object->getCostDescription()
        ), $dbObjects));
    }

    public function removeByID(string $objID) {

        $this->createQueryBuilder('trip')
            ->delete(Trip::class,'trip')
            ->where('trip.objID = :objID')
            ->setParameter('objID',$objID)
            ->getQuery()
            ->execute();

        //->andWhere('a.exampleField = :val')

    }

    /**
     * @param string $locationID
     * @return Trip[]|null
     */
    public function getTripsWithLocation (string $locationID):?array {
        $sql = "select distinct trip.* from trip, milestone where milestone.location_id = :locationID and milestone.obj_id = ANY(string_to_array(trip.milestones_ids, ','))";
        $resultSet = new ResultSetMappingBuilder($this->_em);
        $resultSet->addRootEntityFromClassMetadata(Trip::class,'t');
        $qb = $this->_em->createNativeQuery($sql,$resultSet);
        $qb->setParameter('locationID', $locationID, Types::STRING);

        return $qb->getResult();
    }

    /**
     * @param int $userID
     * @return Trip[]|null
     */
    /*public function getUserTrips (int $userID):?array {

    }*/

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
