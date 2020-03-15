<?php


namespace App\Helper;


use App\Entity\Img;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ImageHelper
{
    /**
     * @return string
     * @throws Exception
     */
    public function getRandomName() : string {
        return bin2hex(random_bytes(16));
    }

    public function createImg(UploadedFile $file, Img $img, $name, $storagePath) : Img {
        $path = $storagePath . '/' . $name. '.' . $file->getClientOriginalExtension();
        $img->setName($name);
        $img->setPath($path);
        $file->move($storagePath, $name.'.'.$file->getClientOriginalExtension());
        return $img;
    }

    public function removeImg(Img $img) : void {
        $fs = new Filesystem();
        $fs->remove($img->getPath());
    }
}