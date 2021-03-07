<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    const PERPAGE = 9;
    /**
     * @Route("/", name="app_home",methods={"GET"})
     */
    public function index(
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        Request $request
    ): Response {
        $totalPost = count($postRepository->findAll());
        $totalPage = ($totalPost == $this::PERPAGE) ? 1 :  floor($totalPost / $this::PERPAGE) + 1;
        $pageCurrent = $request->query->getInt('page');
        $pageCurrent = ($pageCurrent > $totalPage) ? $totalPage : $pageCurrent;
        $pageCurrent = ($pageCurrent == 0) ? 1 : $pageCurrent;
        $pagination = $postRepository->getPaginatedPost($this::PERPAGE, $pageCurrent, $totalPage);
        $categories = $categoryRepository->findAll();
        $categoryNumber = null;
        return $this->render(
            'home/index.html.twig',
            compact('categories', 'categoryNumber', 'pagination', 'totalPage', 'pageCurrent')
        );
    }
    /**
     * @Route("/post/{id}", name="app_show")
     */
    public function show(Post $post): Response
    {
        return $this->render('home/show.html.twig', compact('post'));
    }
    /**
     * @Route("/category/{id}", name="app_category")
     */
    public function category(Category $category, CategoryRepository $categoryRepository): Response
    {

        $categoryNumber = $category->getId();
        $pagination = $category->getPosts();
        $categories = $categoryRepository->findAll();
        return $this->render('home/index.html.twig', compact('pagination', 'categories', 'categoryNumber', 'category'));
    }
}
