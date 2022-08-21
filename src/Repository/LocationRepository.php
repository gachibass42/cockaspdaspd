<?php

namespace App\Repository;

use App\Entity\Location;
use App\Modules\Syncer\Model\SyncObjectLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    /**
     * @return Location[]
     */
    public function searchByText(string $text): array
    {
        return $this->createQueryBuilder('l')
            ->where('l.name LIKE :value')
            ->orWhere('l.address LIKE :value')
            ->orWhere('l.searchTags LIKE :value')
            ->setParameter('value', "%{$text}%")
            ->orderBy('l.objID', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function save (Location $location) {
        $this->getEntityManager()->persist($location);
        //$this->getEntityManager()->flush();
    }

    public function commit () {
        $this->getEntityManager()->flush();
    }

    public function getObjectsForSync (\DateTimeImmutable $lastSyncDate): array {
        $dbObjects = $this->createQueryBuilder('object')
            ->where('object.syncDate > :value')
            ->setParameter('value', $lastSyncDate)
            ->orderBy('object.objID', 'ASC')
            ->getQuery()
            ->getResult()
            ;
        return (array_map(fn(Location $object) => new SyncObjectLocation(
            $object->getObjID(),
            $object->getSyncDate()->getTimestamp(),
            "update",
            $object->getName(),
            $object->getLat(),
            $object->getLon(),
            $object->getAddress(),
            $object->getTimeZone(),
            $object->getCodeIATA(),
            $object->getCountryCode(),
            $object->getExternalPlaceId(),
            $object->getSearchTags(),
            $object->getInternationalName(),
            $object->getInternationalAddress(),
            $object->getCityLocation() ? $object->getCityLocation()->getObjID() : null,
            $object->getCountryLocation() ? $object->getCountryLocation()->getObjID() : null,
            $object->getType(),
            $object->getOwner() ? (string)$object->getOwner()->getId() : null,
            $object->getPhoneNumber(),
            $object->getWebsite(),
            $object->getDescription()
        ),$dbObjects));
    }

    public function removeByID(string $objID) {

        $this->createQueryBuilder('t')
            ->delete(Location::class)
            ->where('t.objID = :objID')
            ->setParameter('objID',$objID)
            ->getQuery()
            ->execute();

        //->andWhere('a.exampleField = :val')

    }
}
