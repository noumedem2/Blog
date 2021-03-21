<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    /**
     * @Route("/{id}", name="app_comment_delete", methods={"DELETE"})
     * @return Response
     */
    public function delete(Request $request, Comment $comment): Response
    {
        $postId = $comment->getPost()->getId();
        $user = $this->getUser();
        if ($this->isCsrfTokenValid(
            'delete' . $comment->getId(),
            $request->request->get('_token')
        )) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash('success', "Comemntaire Supprimer");
            return $this->redirectToRoute("app_show", ['id' => $postId]);
        }
        $this->addFlash('error', "Csrf token invalid");
        return $this->redirectToRoute("app_show", ['id' => $postId]);
    }

    /**
     * @Route("/{id}/edit", name="app_comment_edit", methods={"GET"})
     * @return Response
     */
    public function edit(Comment $comment,SessionInterface $session): Response
    {
        $session->set('editComment',$comment->getId());
        $postId = $comment->getPost()->getId();
        return $this->redirectToRoute("app_show", [
            'id' => $postId,
        ]);
    }
}
