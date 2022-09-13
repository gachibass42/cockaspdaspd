<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Modules\Syncer\Model\SyncObjectComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Comment $entity, bool $flush = true): void
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
    public function remove(Comment $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /*public function getObjectsForSync (\DateTime $lastSyncDate): array {
        return $this->createQueryBuilder('object')
            ->where('object.syncDate > :value')
            ->setParameter('value', $lastSyncDate)
            ->orderBy('object.objID', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }*/

    public function save (Comment $comment) {
        $this->getEntityManager()->persist($comment);
        //$this->_em->flush();
    }

    public function getObjectsForSync (\DateTimeImmutable $lastSyncDate, array $linkedObjIDs): array {
        $expr = $this->_em->getExpressionBuilder();
        $dbObjects = $this->createQueryBuilder('object')
            ->where($expr->in('object.linkedObjID', $linkedObjIDs))
            ->andWhere('object.syncDate > :value')
            ->setParameter('value', $lastSyncDate)
            ->orderBy('object.objID', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        return (array_map(fn(Comment $object) => new SyncObjectComment(
            $object->getSyncDate()->getTimestamp(),
            $object->getObjId(),
            $object->getLinkedObjID(),
            $object->getType(),
            $object->getOwner() ? $object->getOwner()->getId() : null,
            $object->getImages(),
            $object->getTags(),
            $object->getDate()->getTimestamp(),
            $object->getContent()
        ), $dbObjects));
    }

    public function removeByID(string $objID) {

        $this->createQueryBuilder('comment')
            ->delete(Comment::class,'comment')
            ->where('comment.objID = :objID')
            ->setParameter('objID',$objID)
            ->getQuery()
            ->execute();
    }

    public function clearComments (int $userID) {
        $this->createQueryBuilder('comment')
            ->delete('App:Comment','comment')
            ->where('comment.owner = :userID')
            ->setParameter('userID', $userID)
            ->getQuery()
            ->execute();
    }

    public function getReviewsNumberForObject (string $linkedObjID): int {
        $expr = $this->_em->getExpressionBuilder();
        $reviewsNumber = $this->createQueryBuilder('comment')
            ->select($expr->count('comment.objID'))
            ->where('comment.linkedObjID = :linkedObjID')
            ->andWhere('comment.type = :type')
            ->setParameter('linkedObjID', $linkedObjID)
            ->setParameter('type', 'review')
            ->getQuery()
            ->getResult()
        ;
        //dump ($reviewsNumber[0][1]);
        return $reviewsNumber[0][1] ?? 0;
    }

    /**
     * @param string[] $linkedObjIDs
     * @return Comment[]
     */
    public function getReviewsForObjects(array $linkedObjIDs): array {
        $expr = $this->_em->getExpressionBuilder();
        return $this->createQueryBuilder('comment')
            ->where($expr->in('comment.linkedObjID', $linkedObjIDs))
            ->andWhere('comment.type = :type')
            ->setParameter('type', 'review')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string[] $linkedObjIDs
     * @return Comment[]
     */
    public function getObjectsComments (array $linkedObjIDs): array{
        $expr = $this->_em->getExpressionBuilder();
        return $this->createQueryBuilder('object')
            ->where($expr->in('object.linkedObjID', $linkedObjIDs))
            ->orderBy('object.objID', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
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
    public function findOneBySomeField($value): ?Comment
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
