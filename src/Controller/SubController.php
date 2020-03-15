<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SubController extends AbstractController
{
    /**
     * @Route("/sub", name="sub")
     */
    public function index()
    {
        return $this->render('sub/index.html.twig', [
            'controller_name' => 'SubController',
        ]);
    }
}
