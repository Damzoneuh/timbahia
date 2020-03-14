<?php


namespace App\Helper;


use App\Entity\Diary;

trait DiaryHelper
{
    public function createDiaryArray(Diary $diary){
        return [
            'title' => $diary->getTitle(),
            'text' => $diary->getDescription(),
            'img' => $diary->getImg() ? $diary->getImg()->getId() : null,
            'id' => $diary->getId(),
            'date' => $diary->getDate()->format('d/m/y H:i')
        ];
    }
}