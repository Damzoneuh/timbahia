<?php

namespace App\Controller;

use App\Entity\Diary;
use App\Entity\User;
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

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/sub/event/{id}", name="sub_event")
     */
    public function subEvent($id){
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $diary = $em->getRepository(Diary::class)->find($id);
        $user->addDiary($diary);
        $em->flush();
        $this->addFlash('success', 'Votre inscription à l\'évènement ' . $diary->getTitle() . ' à bien été pris en compte');
        return $this->redirectToRoute('parameter');
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/sub/unsub/event/{id}", name="sub_unsub_event")
     */
    public function unsubEvent($id){
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $diary = $em->getRepository(Diary::class)->find($id);
        $user->removeDiary($diary);
        $em->flush();
        $this->addFlash('success', 'Votre désinscription à l\'évènement ' . $diary->getTitle() . ' à bien été pris en compte');
        return $this->redirectToRoute('parameter');
    }
}
