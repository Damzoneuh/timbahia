<?php

namespace App\Controller;

use App\Entity\Img;
use App\Entity\News;
use App\Helper\ImageHelper;
use App\Helper\NewsHelper;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    use NewsHelper;
    use ImageHelper;
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

    /**
     * @param Request $request
     * @param null $id
     * @return RedirectResponse|Response
     * @throws Exception
     * @Route("/admin/news/{id}", name="admin_news")
     */
    public function getAdminNews(Request $request,$id = null){
        if (!$id){
            $news = $this->getDoctrine()->getRepository(News::class)->findAll();
            return $this->render('admin/news.html.twig', ['news' => $news]);
        }

        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository(News::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'data' => $news->getTitle()
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Texte',
                'data' => $news->getText()
            ])
            ->add('file', FileType::class, [
                'label' => 'Image',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $file = $data['file'];
            $news->setTitle($data['title']);
            $news->setText($data['text']);
            if ($file){
                if ($news->getImg()){
                    $this->removeImg($news->getImg());
                    $em->remove($news->getImg());
                }
                $name = $this->getRandomName();
                $image = new Img();
                $imgTrait = $this->createImg($file, $image, $name, $this->getParameter('app.storage'));
                $em->persist($imgTrait);
                $news->setImg($imgTrait);
            }
            $em->flush();
            $this->addFlash('success', 'La publication à bien été modifiée');
            return $this->redirectToRoute('admin_news');
        }
        return $this->render('admin/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @Route("/admin/create/news", name="admin_create_news")
     * @return Response
     * @throws Exception
     */
    public function createAdminNews(Request $request){
        $form = $this->createFormBuilder()
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Texte'
            ])
            ->add('file', FileType::class, [
                'label' => 'Image'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $news = new News();
            $file = $data['file'];
            $news->setTitle($data['title']);
            $news->setText($data['text']);
            if ($file) {
                $name = $this->getRandomName();
                $image = new Img();
                $imgTrait = $this->createImg($file, $image, $name, $this->getParameter('app.storage'));
                $em->persist($imgTrait);
                $news->setImg($imgTrait);
            }
            $em->persist($news);
            $em->flush();
            $this->addFlash('success', 'La publication à bien été créé');
            return $this->redirectToRoute('admin_news');
        }

        return $this->render('admin/create-news.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @Route("/admin/delete/news/{id}", name="admin_delete_news")
     */
    public function delete($id){
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository(News::class)->find($id);
        if ($news->getImg()){
            $this->removeImg($news->getImg());
        }
        $em->remove($news);
        $em->flush();
        $this->addFlash('success', 'La publication à bien été supprimée');
        return $this->redirectToRoute('admin_news');
    }
}
