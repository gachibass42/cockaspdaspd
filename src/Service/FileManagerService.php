<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerService
{
    /**
     * @var string
     */
    private string $avatarImagesDir;

    /**
     * @param $avatarImagesDir
     */
    public function __construct($avatarImagesDir)
    {
        $this->avatarImagesDir = $avatarImagesDir;
    }

    /**
     * @return string
     */
    public function getAvatarImagesDir(): string
    {
        return $this->avatarImagesDir;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function avatarUpload(UploadedFile $file): string
    {
        $fileName = uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->avatarImagesDir,$fileName);
        } catch (FileException $exception) {
            return $exception;
        }

        return $fileName;
    }

    public function saveImage(string $data): ?string
    {
        $filename = uniqid().'.jpeg'; //TODO: check extension
        if (file_put_contents($this->avatarImagesDir.$filename,$data)){
            return $filename;
        }
        return null;
    }
}