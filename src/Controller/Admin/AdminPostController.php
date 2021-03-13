<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\Pagination\PaginationService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post")
 */
class AdminPostController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_post_index", methods={"GET"})
     */
    public function index(
        PostRepository $postRepository,
        PaginationService $paginator,
        Request $request
    ): Response {
        # total element
        $totalPost = $paginator->totalElement($postRepository->findAll());
        # total de page
        $totalPage = $paginator->totalPage($totalPost);
        # page current
        $pageCurrent = $paginator->pageCurrent($request->query->getInt('page'), $totalPage);
        # pagination
        $pagination =  $paginator->pagination("App\Entity\Post", $pageCurrent);

        return $this->render('admin/post/index.html.twig', [
            'posts' => $pagination,
            'totalPage' => $totalPage,
            'pageCurrent' => $pageCurrent
        ]);
    }

    /**
     * @Route("/new", name="app_admin_post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $post->setUpdatedAt(new DateTime('now'));
            $post->setCreatedAt(new DateTime('now'));
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_post_index');
        }

        return $this->render('admin/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('admin/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $post->setUpdatedAt(new DateTime('now'));
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_admin_post_index');
        }

        return $this->render('admin/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_post_index');
    }
}
