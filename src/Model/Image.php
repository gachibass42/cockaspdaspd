<?php

namespace App\Model;

use Symfony\Component\HttpFoundation\File\File;

class Image
{
    /**
     * @var $content
     */
    public string $content;

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

}