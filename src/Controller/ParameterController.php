<?php

namespace App\Controller;

use App\Entity\Diary;
use App\Entity\Instrument;
use App\Entity\Profile;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParameterController extends AbstractController
{
    /**
     * @Route("/parameter", name="parameter")
     * @param Request $request
     * @return RedirectResponse|Response
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $prestations = $this->getDoctrine()->getRepository(Diary::class)->findAll();
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'data' => $user->getProfile() ? $user->getProfile()->getName() : ''
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'data' => $user->getProfile() ? $user->getProfile()->getLastName() : ''
            ])
            ->add('nickName', TextType::class, [
                'label' => 'Surnom',
                'required' => false,
                'data' => $user->getProfile() ? $user->getProfile()->getNickName() : ''
            ]);
        if ($this->isGranted('ROLE_SUB')){
            $instrumentChoices = [];
            $instrumentChoices[''] = '';
            $instruments = $this->getDoctrine()->getRepository(Instrument::class)->findAll();
            $default = null;
            foreach ($instruments as $instrument){
                $instrumentChoices[$instrument->getName()] = $instrument->getId();
                if ($user->getProfile() && $user->getProfile()->getInstrument()){
                    if($user->getProfile()->getInstrument()->getId() == $instrument->getId()){
                        $default = $instrument->getId();
                    }
                }
            }
            $form->add('instrument', ChoiceType::class, [
                'choices' => $instrumentChoices
            ]);
        }
        $form->add('submit', SubmitType::class, [
            'label' => 'Valider'
        ]);
        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            try {
                $data = $form->getData();
                $em = $this->getDoctrine()->getManager();
                if (!$this->getUser()->getProfile()){
                    $profile = new Profile();
                    $em->persist($profile);
                    $user->setProfile($profile);
                    $em->flush();
                }
                $profile = $user->getProfile();
                $profile->setName($data['name']);
                $profile->setLastName($data['lastName']);
                $profile->setNickName($data['nickName']);
                if ($data['instrument']){
                    $instrument = $em->getRepository(Instrument::class)->find($data['instrument']);
                    $profile->setInstrument($instrument);
                }
                $em->flush();
            }
            catch (\Exception $e){
                $this->addFlash('error', 'Une erreur est survenue lors de la validation, si le problème persiste merci de nous contacter .');
                return $this->redirectToRoute('parameter');
            }

            $this->addFlash('success', 'Votre compte à bien été mis à jour');
            return $this->redirectToRoute('parameter');
        }
        return $this->render('parameter/index.html.twig', ['form' => $form->createView(), 'prestations' => $prestations]);
    }
}
