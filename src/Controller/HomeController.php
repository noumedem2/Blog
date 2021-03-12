<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\Pagination\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $pagination = $postRepository->getPaginatedPost(
            $paginator::PERPAGE,
            $pageCurrent,
            $totalPage
        );

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
     * @Route("/post/{id}", name="app_show")
     */
    public function show(Post $post): Response
    {
        return $this->render('home/show.html.twig', compact('post'));
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

        $categoryNumber = $category->getId();
        #total Element
        $totalElement =  $paginator->totalElement($category->getPosts());
        # total page
        $totalPage = $paginator->totalPage($totalElement);
        # page current
        $pageCurrent =  $paginator->pageCurrent($request->query->getInt('page'), $totalPage);
        # pagination

        $pagination = $categoryRepository->getPaginatedCategory(
            $categoryNumber,
            $paginator::PERPAGE,
            $pageCurrent,
            $totalPage
        );
        return $this->render('home/index.html.twig', [
            'pagination' => $pagination,
            'categories' => $categoryRepository->findAll(),
            'categoryNumber' => $categoryNumber,
            'category' => $category,
            'totalPage' => $totalPage,
            'pageCurrent' => $pageCurrent
        ]);
    }
}
