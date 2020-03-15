<?php

namespace App\Controller;

use App\Entity\Diary;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/diary", name="admin_diary")
     */
    public function diary(){
        $diaries = $this->getDoctrine()->getRepository(Diary::class)->findAll();
        return $this->render('admin/diaries.html.twig', ['diaries' => $diaries]);
    }

    /**
     * @return Response
     * @Route("/sa/users/{id}", name="super_admin_users")
     */
    public function getUsers(Request $request, $id = null){
        if (!$id){
            $users = $this->getDoctrine()->getRepository(User::class)->findAll();
            return $this->render('admin/users.html.twig', ['users' => $users]);
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Membre' => 'ROLE_SUB',
                    'Préstations' =>'ROLE_PRESTA',
                    'Administrateur / éditeur' => 'ROLE_ADMIN',
                    'Super administrateur' => 'ROLE_SUPER_ADMIN',
                    '' => ''
                ],
                'label' => 'Droits'
            ])
            ->add('sub', SubmitType::class, [
                'label' => 'Valider'
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $user->setRoles([$data['role']]);
            $em->flush();
            $this->addFlash('success', 'Le role à bien été ajouté');
            return $this->redirectToRoute('super_admin_users');
        }
        return $this->render('admin/user.html.twig', ['user' => $user, 'form' => $form->createView()]);
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @Route("/sa/delete/user/{id}", name="super_admin_delete_user")
     */
    public function deleteUser($id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'L\'utilisateur à bien été supprimé');
        return $this->redirectToRoute('super_admin_users');
    }
}
