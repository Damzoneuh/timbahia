<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     * @throws \Exception
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, MailerService $service)
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Retapez votre mot de passe'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $knowEmail = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            if ($data['password'] == $data['plainPassword']){
                if ($knowEmail){
                    $this->addFlash('error', 'Cet email éxiste déjà');
                    return $this->render('registration/index.html.twig', ['form' => $form->createView()]);
                }
                $user = new User();
                $user->setEmail($data['email']);
                $password = $encoder->encodePassword($user, $data['password']);
                $user->setPassword($password);
                $user->setIsValidated(false);
                $token = bin2hex(random_bytes(20));
                $user->setResetToken($token);

                if ($service->sendRegistrationMail($user->getEmail(), $user->getResetToken())){
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success', 'Un mail de confiramtion à été envoyé à : ' . $user->getEmail());
                    return $this->redirectToRoute('index');
                }
                $this->addFlash('error', 'Une erreur est survenue lors de la création de votre compte');
            }
            $this->addFlash('error', 'Les mots de passe ne sont pas identiques .');
            return $this->render('registration/index.html.twig', ['form' => $form->createView()]);
        }
        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/register/mail/{token}", name="register_mail")
     */
    public function registerMail($token){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['resetToken' => $token]);
        if ($user){
            $user->setResetToken(null);
            $em->flush();
            $this->addFlash('success', 'Votre compte est désormais actif');
            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('error', 'Votre authentification à échoué veuillez contacter un administrateur');
        return $this->redirectToRoute('index');
    }
}
