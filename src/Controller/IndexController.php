<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\AssociationDetails;
use App\Entity\AssociationHourly;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('index/index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/api/header", name="api_header", methods={"GET"})
     */
    public function getHeader(){
        return $this->json(['Luciano, et toute son équipe vous accueillent pour vibrer au son du Samba Reggea à Annecy ou Lyon débutant ou avancé .']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/api/assoc", name="api_assoc")
     */
    public function getAssociationInformation(){
        $assocs = $this->getDoctrine()->getRepository(AssociationDetails::class)->findAll();
        $data = [];
        foreach ($assocs as $assoc){
            if ($assoc){
                $classes = $assoc->getCity()->getAssociationHourlies();
                $hours = [];
                if ($classes->count() > 0){
                    foreach ($classes->getValues() as $hour){
                        /** @var AssociationHourly $hour */
                        array_push($hours, [
                            'day' => $hour->getDay(),
                            'start' => $hour->getStartHour(),
                            'end' => $hour->getEndHour()
                        ]);
                    }
                    array_push($data, [
                        'name' => $assoc->getCity()->getName(),
                        'city' => $assoc->getCity()->getCity(),
                        'id' => $assoc->getCity()->getId(),
                        'hours' => $hours
                    ]);
                }
            }
        }
        return $this->json($data);
    }
}
