<?php

namespace App\Helpers\FileManager;

class ImagesManager
{
    private string $thumbnailsDir;
    public function __construct($thumbnailsDir, private FileManagerService $fileManagerService)
    {
        $this->thumbnailsDir = $thumbnailsDir;
    }

    public function saveImage(string $data, string $name): ?string {
        $image = imagecreatefromstring($data);
        $filePath = pathinfo($name);
        $scaledImage = imagescale($image, 320);
        imagewebp($scaledImage,$this->thumbnailsDir.$filePath['filename'].'.webp', quality: 30);
        return $this->fileManagerService->saveImage($data,$name);
    }

    public function getThumbnailDataForImage (string $name): string {
        $filePath = pathinfo($name);
        if (!file_exists($this->thumbnailsDir.$filePath['filename'].'.webp')) {
            $image = imagecreatefromstring($this->fileManagerService->getImageContent($name));
            $scaledImage = imagescale($image, 320);
            imagewebp($scaledImage,$this->thumbnailsDir.$filePath['filename'].'.webp', quality: 30);
        }
        return file_get_contents($this->thumbnailsDir.$filePath['filename'].'.webp');
    }
}