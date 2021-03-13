<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\Pagination\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/user")
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_user_index", methods={"GET"})
     */
    public function index(
        UserRepository $userRepository,
        PaginationService $paginator,
        Request $request
    ): Response {
        # total element
        $totalPost = $paginator->totalElement($userRepository->findAll());
        # total de page
        $totalPage = $paginator->totalPage($totalPost);
        # page current
        $pageCurrent = $paginator->pageCurrent($request->query->getInt('page'), $totalPage);
        # pagination
        $pagination =  $paginator->pagination("App\Entity\User", $pageCurrent);
        return $this->render('admin/user/index.html.twig', [
            'users' => $pagination,
            'totalPage' => $totalPage,
            'pageCurrent' => $pageCurrent
        ]);
    }



    /**
     * @Route("/{id}/edit", name="app_admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_admin_user_index');
        }
        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_index');
    }
}
