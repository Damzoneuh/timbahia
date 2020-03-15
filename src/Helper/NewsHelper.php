<?php


namespace App\Helper;

use App\Entity\News;


trait NewsHelper
{
    public function createGetArray(News $new){
        return [
            'title' => $new->getTitle(),
            'text' => $new->getText(),
            'img' => $new->getImg() ? $new->getImg()->getId() : null,
            'id' => $new->getId()
        ];
    }
}