<?php

namespace App\Modules\Comments\Model;

class CommentsResponse
{

    /**
     * @var CommentItem[]|null
     */
    public ?array $items;

    /**
     * @param CommentItem[]|null $items
     */
    public function __construct(?array $items)
    {
        $this->items = $items;
    }


}