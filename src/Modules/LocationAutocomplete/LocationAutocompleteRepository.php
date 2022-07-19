<?php

namespace App\Modules\LocationAutocomplete;
use App\Entity\Location;
use App\Modules\LocationAutocomplete\Model\LocationPredictionsItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationAutocompleteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    /**
     * @return LocationPredictionsItem[]
     */
    public function searchByText(string $text): array
    {
        return array_map(fn (Location $location) => new LocationPredictionsItem(
                $location->getObjID(),
                $location->getName(),
                $location->getExternalPlaceId(),
                $location->getAddress(),
                $location->getType(),
            ),
            $this->createQueryBuilder('l')
                ->where('l.name LIKE :value')
                ->orWhere('l.address LIKE :value')
                ->orWhere('l.searchTags LIKE :value')
                ->setParameter('value', "%{$text}%")
                ->orderBy('l.objID', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
        );
    }
}