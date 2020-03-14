<?php

namespace App\Controller;

use App\Entity\News;
use App\Helper\NewsHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    use NewsHelper;
    /**
     * @Route("/news", name="news")
     */
    public function index()
    {
        return $this->render('news/index.html.twig');
    }

    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/api/news/{id}", name="api_news", methods={"GET"})
     */
    public function getNews($id = null){
        $data = [];
        if ($id){
            $new = $this->getDoctrine()->getRepository(News::class)->find($id);
            if (!$new){
                throw new NotFoundHttpException();
            }
            return $this->json($this->createGetArray($new));
        }

        $news = $this->getDoctrine()->getRepository(News::class)->findAll();
        if (count($news) > 0){
            foreach ($news as $new){
                array_push($data, $this->createGetArray($new));
            }
        }

        return $this->json($data);
    }
}
