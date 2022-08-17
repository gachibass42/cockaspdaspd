<?php

namespace App\Modules\Comments\Model;

class CommentItem
{
    public string $objID;
    public string $linkedObjID;
    public ?string $type;
    public ?string $userID;

    public ?array $images;

    public ?array $tags;
    public int $date;
    public ?string $content;

    /**
     * @param string $objID
     * @param string $linkedObjID
     * @param string|null $type
     * @param string|null $userID
     * @param string[]|null $images
     * @param string[]|null $tags
     * @param int $date
     * @param string|null $content
     */
    public function __construct(string $objID, string $linkedObjID, ?string $type, ?string $userID, ?array $images, ?array $tags, int $date, ?string $content)
    {
        $this->objID = $objID;
        $this->images = $images;
        $this->linkedObjID = $linkedObjID;
        $this->type = $type;
        $this->userID = $userID;
        $this->tags = $tags;
        $this->date = $date;
        $this->content = $content;
    }


}