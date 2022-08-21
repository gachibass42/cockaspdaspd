<?php

namespace App\Repository;

use App\Entity\CheckList;
use App\Modules\Syncer\Model\SyncObjectCheckList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CheckList>
 *
 * @method CheckList|null find($id, $lockMode = null, $lockVersion = null)
 * @method CheckList|null findOneBy(array $criteria, array $orderBy = null)
 * @method CheckList[]    findAll()
 * @method CheckList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CheckList::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CheckList $entity, bool $flush = true): void
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
    public function remove(CheckList $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getObjectsForSync (\DateTimeImmutable $lastSyncDate, array $checkListsIDs): array {
        /*$dbObjects =  $this->createQueryBuilder('object')
            ->where('object.syncDate > :value')
            ->setParameter('value', $lastSyncDate)
            ->orderBy('object.objID', 'ASC')
            ->getQuery()
            ->getResult()
            ;*/
        $expr = $this->_em->getExpressionBuilder();
        $dbObjects = $this->createQueryBuilder('object')
            ->where($expr->in('object.objID', $checkListsIDs))
            ->andWhere('object.syncDate > :value')
            ->setParameter('value', $lastSyncDate)
            ->orderBy('object.objID', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        return (array_map(fn(CheckList $object) => new SyncObjectCheckList(
            $object->getObjId(),
            $object->getName(),
            $object->getSyncDate()->getTimestamp(),
            $object->getType(),
            $object->getBoxes()
        ),$dbObjects));
    }

    public function save(CheckList $checkList) {
        $this->getEntityManager()->persist($checkList);
    }

    public function removeByID(string $objID) {

        $this->createQueryBuilder('checkList')
            ->delete('App:CheckList','checkList')
            ->where('checkList.objID = :objID')
            ->setParameter('objID',$objID)
            ->getQuery()
            ->execute();

    }

    // /**
    //  * @return CheckList[] Returns an array of CheckList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CheckList
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
