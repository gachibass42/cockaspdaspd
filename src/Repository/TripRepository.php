<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Entity\TripUserRole;
use App\Modules\Syncer\Model\SyncObjectTrip;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;

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
    public function save (Trip $trip, bool $flush = false) {
        $this->getEntityManager()->persist($trip);
        if ($flush) {
            $this->_em->flush();
        }
        //
    }

    /**
     * @param DateTimeImmutable $lastSyncDate
     * @param int $userID
     * @return SyncObjectTrip[]
     */
    public function getObjectsForSync (DateTimeImmutable $lastSyncDate, int $userID): array {
        /*$expr = $this->_em->getExpressionBuilder();
        $rolesTrips = $this->createQueryBuilder('r')
            ->select('roles.trip')
            ->from(TripUserRole::class,'roles')
            ->where('roles.tripUser = :userid')
            ->setParameter('userid', $userID)
            ->getDQL();
        $dbObjects = $this->createQueryBuilder('object')
            ->where('object.syncDate > :value')
            ->andWhere($expr->orX(
                'object.owner = :userid',
                $expr->in('object.objID',$rolesTrips)
            ))
            ->setParameter('value', $lastSyncDate)
            ->setParameter('userid', $userID)+
            ->orderBy('object.objID', 'ASC')
            ->getQuery()
            ->getResult()
            ;*/
        $sql = "select t.* from trip t where (owner_id = :userid or obj_id in (select r.trip_id from trip_user_role r where r.trip_user_id = :userid)) and sync_date > :syncDate";
        $resultSet = new ResultSetMappingBuilder($this->_em);
        $resultSet->addRootEntityFromClassMetadata(Trip::class,'t');
        $qb = $this->_em->createNativeQuery($sql,$resultSet);
        $qb->setParameter('syncDate', $lastSyncDate, Types::DATETIME_IMMUTABLE);
        $qb->setParameter('userid', $userID, Types::INTEGER);
        $dbObjects = $qb->getResult();

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


    /**
     * @param string[] $tripsIDs
     * @return Trip[]
     */
    public function getTripsByIDs (array $tripsIDs): array {
        return $this->createQueryBuilder('trip')
            ->where('trip.objID in (:tripsIDs)')
            ->setParameter('tripsIDs',$tripsIDs,Connection::PARAM_STR_ARRAY)
            ->getQuery()
            ->getResult();
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
        $sql = "select distinct t.* from trip t, milestone where t.visibility = true and milestone.location_id = :locationID and milestone.obj_id = ANY(string_to_array(t.milestones_ids, ','))";
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
