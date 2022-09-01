<?php

namespace App\Modules\Comments;

use App\Entity\Comment;
use App\Helpers\FileManager\FileManagerService;
use App\Helpers\FileManager\ImagesManager;
use App\Modules\Comments\Model\CommentItem;
use App\Modules\Comments\Model\CommentsResponse;
use App\Repository\CommentRepository;

class CommentsService
{

    public function __construct(private CommentRepository $commentRepository, private ImagesManager $imagesManager)
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
            $comment->getOwner()->getId(),
            $comment->getImages() != null ? array_map(fn (string $imageName) => base64_encode($this->imagesManager->getThumbnailDataForImage($imageName)),$comment->getImages()) : [],
            $comment->getTags(),
            $comment->getDate()->getTimestamp(),
            $comment->getContent()
        ),$this->commentRepository->findBy(['linkedObjID' => $linkedObjID]));
    }
}