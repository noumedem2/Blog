<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {
        $posts = $postRepository->findAll();
        $categories = $categoryRepository->findAll();
        return $this->render('home/index.html.twig', compact('posts', 'categories'));
    }
    /**
     * @Route("/post/{id}", name="app_show")
     */
    public function show(Post $post): Response
    {
        return $this->render('home/show.html.twig', compact('post'));
    }
}
