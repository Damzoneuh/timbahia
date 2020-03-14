<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DiaryController extends AbstractController
{
    /**
     * @Route("/diary", name="diary")
     */
    public function index()
    {
        return $this->render('diary/index.html.twig', [
            'controller_name' => 'DiaryController',
        ]);
    }
}
