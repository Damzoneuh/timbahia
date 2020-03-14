<?php

namespace App\Controller;

use App\Entity\AssociationDetails;
use App\Entity\Link;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class LayoutController extends AbstractController
{
    private $_serializer;

    public function __construct()
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $this->_serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @Route("/api/nav/link", name="api_nav_link", methods={"GET"})
     */
    public function index()
    {
        $links = $this->getDoctrine()->getRepository(Link::class)->findAll();
        $data = [];
        $data['left'] = [];
        $data['right'] = [];
        foreach ($links as $link){
            if($link->getIsLeft()){
                array_push($data['left'], [
                    'path' => $link->getPath(),
                    'name' => $link->getName(),
                    'id' => $link->getId()
                ]);
             }
            else{
                array_push($data['right'], [
                    'path' => $link->getPath(),
                    'name' => $link->getName(),
                    'id' => $link->getId()
                ]);
            }
        }
        return $this->json($data);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/api/footer/form", name="api_footer_form", methods={"GET"})
     */
    public function getFooterForm(){
        $assocs = $this->getDoctrine()->getRepository(AssociationDetails::class)->findAll();
        $data = [];
        foreach ($assocs as $assoc){
            array_push($data, [
                'id' => $assoc->getCity()->getId(),
                'city' => $assoc->getCity()->getCity(),
                'email' => $assoc->getEmail()
            ]);
        }
        return $this->json($data);
    }

    /**
     * @param Request $request
     * @param MailerService $mailerService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @Route("/api/contact/send", name="api_contact_send", methods={"POST"})
     */
    public function sendContactMail(Request $request, MailerService $mailerService){
        $data = $this->_serializer->decode($request->getContent(), 'json');
        $mailerService->sendContactMessage($data['target'], $data['sender'], $data['text']);
        return $this->json(true);
    }
}
