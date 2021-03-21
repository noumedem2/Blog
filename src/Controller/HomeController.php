<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\Pagination\PaginationService;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home",methods={"GET"})
     */
    public function index(
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        Request $request,
        PaginationService $paginator
    ): Response {

        # total element
        $totalPost = $paginator->totalElement($postRepository->findAll());
        # total de page
        $totalPage = $paginator->totalPage($totalPost);
        # page current
        $pageCurrent = $paginator->pageCurrent($request->query->getInt('page'), $totalPage);
        # pagination
        $pagination =  $paginator->pagination("App\Entity\Post", $pageCurrent);

        return $this->render(
            'home/index.html.twig',
            [
                'categories' => $categoryRepository->findAll(),
                'categoryNumber' => null,
                'pagination' => $pagination,
                'totalPage' => $totalPage,
                'pageCurrent' => $pageCurrent
            ]
        );
    }



    /**
     * @Route("/post/{id}", name="app_show",methods={"GET","POST"})
     */
    public function show(Post $post, Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $editComment = $session->get('editComment');
        if ($editComment != null) {
            $comment = $em->getRepository('App\Entity\Comment')->findOneBy(['id' => $editComment]);
        } else {
            $comment = new Comment();
        }
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setPost($post);
            $comment->setCreatedAt(new DateTime('now'));
            $comment->setUpdatedAt(new DateTime('now'));
            $em->persist($comment);
            $em->flush($comment);
            $session->remove('editComment');
            $this->addFlash('success', "Commentaire Ajoute");
            return   $this->redirectToRoute('app_show', ['id' => $post->getId()]);
        }
        return $this->render(
            'home/show.html.twig',
            [
                'post' => $post,
                'comments' => $post->getComments(),
                'form' => $form->createView(),
                'editComment' => $editComment
            ]
        );
    }




    /**
     * @Route("/category/{id}", name="app_category")
     */
    public function category(
        Category $category,
        CategoryRepository $categoryRepository,
        Request $request,
        PaginationService $paginator
    ): Response {

        #total Element
        $totalElement =  $paginator->totalElement($category->getPosts());
        # total page
        $totalPage = $paginator->totalPage($totalElement);
        # page current
        $pageCurrent =  $paginator->pageCurrent($request->query->getInt('page'), $totalPage);
        # pagination
        $pagination = $paginator->pagination("App\Entity\Post", $pageCurrent, "Category", $category->getId());
        return $this->render('home/index.html.twig', [
            'pagination' => $pagination,
            'categories' => $categoryRepository->findAll(),
            'categoryNumber' => $category->getId(),
            'category' => $category,
            'totalPage' => $totalPage,
            'pageCurrent' => $pageCurrent
        ]);
    }
}
