<?php

namespace App\Modules\Comments;

use App\Entity\Comment;
use App\Helpers\FileManager\ImagesManager;
use App\Modules\Comments\Model\CommentItem;
use App\Modules\Comments\Model\CommentUserShort;
use App\Modules\Comments\Model\ImageItem;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\UrlHelper;

class CommentsService
{

    public function __construct(private CommentRepository $commentRepository,
                                private ImagesManager $imagesManager,
                                private UrlHelper $urlHelper)
    {
    }


    /**
     * @param string $linkedObjID
     * @return CommentItem[]
     */
    public function getCommentsForObject (string $linkedObjID): array {
        return array_map(fn (Comment $comment) => new CommentItem(
            $comment->getObjId(),
            $comment->getLinkedObjID(),
            $comment->getType(),
            new CommentUserShort(
                $comment->getOwner()->getId(),
                $comment->getOwner()->getName(),
                base64_encode($this->imagesManager->getThumbnailDataForImage($comment->getOwner()->getAvatar()))
            ),
            $comment->getImages() != null ? array_map(
                fn (string $imageName) => new ImageItem(
                    str_replace('http','https',$this->urlHelper->getAbsoluteUrl('/api/v1/image/'.$imageName)),
                    base64_encode($this->imagesManager->getThumbnailDataForImage($imageName))
                ),$comment->getImages()
            ) : [],
            $comment->getTags(),
            $comment->getDate()->getTimestamp(),
            $comment->getContent()
        ),$this->commentRepository->findBy(['linkedObjID' => $linkedObjID]));
    }

    /**
     * @param string[] $objectsIDs
     * @return CommentItem[]
     */
    public function getCommentsForObjectsArray (array $objectsIDs): array {
        $result = [];
        foreach ($objectsIDs as $objectID) {
            $result = array_merge($result, $this->getCommentsForObject($objectID));
        }
        return $result;//array_map (fn (string $objID) => $this->getCommentsForObject($objID),$objectsIDs);
        //return [];
    }
}