<?php

namespace App\Controller;

use App\Entity\Diary;
use App\Entity\Img;
use App\Helper\DiaryHelper;
use App\Helper\ImageHelper;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DiaryController extends AbstractController
{
    use DiaryHelper;
    use ImageHelper;
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
        $diaries = $this->getDoctrine()->getRepository(Diary::class)->findBy(['isPublished' => true]);
        if (count($diaries) > 0){
            foreach ($diaries as $diary){
                array_push($data, $this->createDiaryArray($diary));
            }
        }
        return $this->json($data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @Route("/admin/edit/diary/{id}", name="admin_edit_diary")
     * @throws Exception
     */
    public function editDiary(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $diary = $em->getRepository(Diary::class)->find($id);

        $form = $this->createFormBuilder()
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'data' => $diary->getTitle()
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Description',
                'data' => $diary->getDescription()
            ])
            ->add('file', FileType::class, [
                'label' => 'Image',
                'required' => false
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date',
                'data' => $diary->getDate(),
                'attr' => [
                    'class' => 'm-auto-select'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Editer'
            ])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){
                $data = $form->getData();
                if ($data['file']){
                    /** @var UploadedFile $file */
                    $file = $data['file'];
                    $name = $this->getRandomName();
                    $img = new Img();
                    $img = $this->createImg($file, $img, $name, $this->getParameter('app.storage'));

                    if ($diary->getImg()){
                        $this->removeImg($diary->getImg());
                        $em->remove($diary->getImg());
                    }
                    $em->persist($img);
                    $diary->setImg($img);
                }
                $diary->setDescription($data['text']);
                $diary->setDate($data['date']);
                $diary->setTitle($data['title']);
                $em->flush();
                $this->addFlash('success', 'La publication à été mise à jour');
                return $this->redirectToRoute('admin_edit_diary', ['id' => $id]);
            }
        return $this->render('admin/diary.html.twig', ['form' => $form->createView(), 'diary' => $diary]);
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @Route("/admin/publish/diary/{id}", name="admin_publish_diary")
     */
    public function publish($id){
        $em = $this->getDoctrine()->getManager();
        $diary = $em->getRepository(Diary::class)->find($id);
        if ($diary->getIsPublished()){
            $diary->setIsPublished(false);
            $em->flush();
            $this->addFlash('success', 'La publication à bien été désactivée');
            return $this->redirectToRoute('admin_edit_diary', ['id' => $id]);
        }
        $diary->setIsPublished(true);
        $em->flush();
        $this->addFlash('success', 'La publication à bien été activée');
        return $this->redirectToRoute('admin_edit_diary', ['id' => $id]);
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @Route("/admin/delete/diary/{id}", name="admin_delete_diary")
     */
    public function deleteDiary($id){
        $em = $this->getDoctrine()->getManager();
        $diary = $em->getRepository(Diary::class)->find($id);
        $this->removeImg($diary->getImg());
        $em->remove($diary);
        $em->flush();

        $this->addFlash('success', 'La publucation à bien été supprimée');
        return $this->redirectToRoute('admin_diary');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     * @Route("/admin/create/diary", name="admin_create_diary")
     */
    public function createDiary(Request $request) {
        $form = $this->createFormBuilder()
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('file', FileType::class, [
                'label' => 'Image',
                'required' => false
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date',
                'attr' => [
                    'class' => 'm-auto-select'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Editer'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $diary = new Diary();
            if ($data['file']){
                /** @var UploadedFile $file */
                $file = $data['file'];
                $name = $this->getRandomName();
                $img = new Img();
                $img = $this->createImg($file, $img, $name, $this->getParameter('app.storage'));
                $em->persist($img);
                $diary->setImg($img);
            }
            $diary->setDescription($data['text']);
            $diary->setDate($data['date']);
            $diary->setTitle($data['title']);
            $diary->setIsPublished(false);
            $em->persist($diary);
            $em->flush();
            $this->addFlash('success', 'La publication à bien été créé');

            return $this->redirectToRoute('admin_diary');
        }

        return $this->render('admin/create-diary.html.twig', ['form' => $form->createView()]);
    }
}
