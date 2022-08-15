<?php

namespace App\Repository;

use App\Entity\Milestone;
use App\Modules\Syncer\Model\SyncObjectMilestone;
use App\Modules\TripsList\Model\ShortMilestone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
//use phpDocumentor\Reflection\Types\Object_;

/**
 * @extends ServiceEntityRepository<Milestone>
 *
 * @method Milestone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Milestone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Milestone[]    findAll()
 * @method Milestone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MilestoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Milestone::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Milestone $entity, bool $flush = true): void
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
    public function remove(Milestone $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function save(Milestone $milestone) {
        $this->getEntityManager()->persist($milestone);
    }

    public function getObjectsForSync (\DateTime $lastSyncDate): array {
        $dbObjects = $this->createQueryBuilder('object')
            ->where('object.syncDate > :value')
            ->setParameter('value', $lastSyncDate)
            ->orderBy('object.objID', 'ASC')
            ->getQuery()
            ->getResult()
            ;
        return (array_map(fn(Milestone $object) => new SyncObjectMilestone(
            $object->getObjID(),
            $object->getName(),
            $object->getSyncDate()->getTimestamp(),
            $object->getCarrier() ? $object->getCarrier()->getObjID() : null,
            $object->getLocationID(),
            $object->getLinkedMilestonesIDs(),
            $object->getUserEdited(),
            $object->getDate()->getTimestamp(),
            $object->getType(),
            $object->getMilestoneDescription(),
            $object->getMilestoneOrder(),
            $object->getJourneyNumber(),
            $object->getSeat(),
            $object->getVagon(),
            $object->getClassType(),
            $object->getTerminal(),
            $object->getDistance(),
            $object->getAircraft(),
            $object->getDuration(),
            $object->getAddress(),
            $object->getWebsite(),
            $object->getPhoneNumber(),
            $object->getIsStartPoint(),
            $object->getIsEndPoint(),
            $object->getInTransit(),
            $object->getRoomNumber(),
            $object->getRentType(),
            $object->getOwner()->getId(),
            $object->getVisibility(),
            count($object->getImages()) > 0 ? array_map(fn ($imageName) => [$imageName => null],$object->getImages()) : null,
            $object->getTags(),
            $object->getMealTimetables(),
            $object->getOrganizationLocationID()),
        $dbObjects));
    }


    /**
     * @param array $milestonesIDs
     * @return ShortMilestone[]
     */
    public function getShortMilestones(array $milestonesIDs): array {
        $sql = "select milestones.obj_id as obj_id, milestones.location_name as name, count(c.obj_id) as reviews_number, milestones.date as milestone_date, milestones.type as type from 
                (select m.obj_id, m.location_id, m.date as date, m.type as type, l.name as location_name from milestone m join location l on m.location_id = l.obj_id where m.obj_id in (:milestonesIDs))
                as milestones left join comment c on c.linked_obj_id = milestones.obj_id and c.type = 'review'
                group by milestones.obj_id, milestones.location_id, milestones.location_name, milestones.date, milestones.type";
        $resultSet = new ResultSetMapping();
        $resultSet->addScalarResult('obj_id', 'objID');
        $resultSet->addScalarResult('name', 'name');
        $resultSet->addScalarResult('type', 'type');
        $resultSet->addScalarResult('reviews_number', 'reviewsNumber','integer');
        $resultSet->addScalarResult('milestone_date', 'milestoneDate','datetime');
        $qb = $this->_em->createNativeQuery($sql,$resultSet);
        $qb->setParameter('milestonesIDs',$milestonesIDs,Connection::PARAM_STR_ARRAY);
        return array_map(fn (array $shortMilestoneScalars) =>
            new ShortMilestone(
                $shortMilestoneScalars['objID']
                ,$shortMilestoneScalars['name']
                ,$shortMilestoneScalars['reviewsNumber']
                ,$shortMilestoneScalars['milestoneDate']->getTimestamp()
                ,$shortMilestoneScalars['type']
            ),
            $qb->getResult()
        );
    }

    public function removeByID(string $objID) {

        $this->createQueryBuilder('milestone')
            ->delete('App:Milestone','milestone')
            ->where('milestone.objID = :objID')
            ->setParameter('objID',$objID)
            ->getQuery()
            ->execute();

        //->andWhere('a.exampleField = :val')

    }

    // /**
    //  * @return Milestone[] Returns an array of Milestone objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Milestone
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
