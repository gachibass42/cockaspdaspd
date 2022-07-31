<?php

namespace App\Modules\Syncer\Model;

class SyncObjectComment
{
    public int $syncStatusDateTime;

    public string $objID;
    public string $linkedObjID;
    public string $type;
    public string $userID;

    public ?array $images;

    public ?array $tags;
    public int $date;
    public ?string $content;

    /**
     * @param int $syncStatusDateTime
     * @param string $objID
     * @param string $linkedObjID
     * @param string $type
     * @param string $userID
     * @param array|null $images
     * @param array|null $tags
     * @param int $date
     * @param string|null $content
     */
    public function __construct(int $syncStatusDateTime, string $objID, string $linkedObjID, string $type, string $userID, ?array $images, ?array $tags, int $date, ?string $content)
    {
        $this->syncStatusDateTime = $syncStatusDateTime;
        $this->objID = $objID;
        $this->linkedObjID = $linkedObjID;
        $this->type = $type;
        $this->userID = $userID;
        $this->images = $images;
        $this->tags = $tags;
        $this->date = $date;
        $this->content = $content;
    }


}