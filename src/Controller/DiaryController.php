<?php

namespace App\Controller;

use App\Entity\Diary;
use App\Helper\DiaryHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DiaryController extends AbstractController
{
    use DiaryHelper;
    /**
     * @Route("/diary", name="diary")
     */
    public function index()
    {
        return $this->render('diary/index.html.twig');
    }

    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/api/diary/{id}", name="api_diary", methods={"GET"})
     */
    public function getDiary($id = null){
        if ($id){
            $diary = $this->getDoctrine()->getRepository(Diary::class)->find($id);
            if ($diary){
                return $this->json($this->createDiaryArray($diary));
            }
            throw new NotFoundHttpException();
        }
        $data = [];
        $diaries = $this->getDoctrine()->getRepository(Diary::class)->findAll();
        if (count($diaries) > 0){
            foreach ($diaries as $diary){
                array_push($data, $this->createDiaryArray($diary));
            }
        }
        return $this->json($data);
    }
}
