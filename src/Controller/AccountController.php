<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Object_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account",methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }
    /**
     * @Route("/account/edit", name="app_account_edit",methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Account updated successfuly !");
            return $this->redirectToRoute('app_account');
        }
        return $this->render('account/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/account/change-password", name="app_account_change_password",methods={"GET","POST"})
     */
    public function change(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(ChangePasswordFormType::class, null, [
            'current_password_is_required' => true
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $newPassword = $form['plainPassword']->getData();
            $user->setPassword($passwordEncoder->encodePassword($user, $newPassword));
            $em->flush();
            $this->addFlash('success', 'Password updated successfuly !');

            return $this->redirectToRoute('app_account');
        }
        $resetForm =   $form->createView();
        return $this->render('account/change_password.html.twig', compact('resetForm'));
    }
}
