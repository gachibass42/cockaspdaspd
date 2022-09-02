<?php

namespace App\Modules\Comments\Model;

class ImageItem
{
    public string $url;
    public string $content;

    /**
     * @param string $url
     * @param string $content
     */
    public function __construct(string $url, string $content)
    {
        $this->url = $url;
        $this->content = $content;
    }


}