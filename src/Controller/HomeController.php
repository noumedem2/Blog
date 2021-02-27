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
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ): Response {

        $dql   = "SELECT p FROM  App\Entity\Post p";
        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );
        $totalPage = floor(count($postRepository->findAll()) / $this::PERPAGE);
        $pageCurrent = $request->query->getInt('page');
        $pageCurrent = ($pageCurrent > $totalPage) ? $totalPage : $pageCurrent;
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
