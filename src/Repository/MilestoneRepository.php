<?php

namespace App\Repository;

use App\Entity\Milestone;
use App\Modules\Syncer\Model\SyncObjectMilestone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
