<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Services\Tools;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="app_users_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, Tools $tools): Response
    {

        $roleuser = $tools->getUser()->getRoles()[0];

        if ($roleuser != null && $roleuser === "ROLE_ADMIN") {
            // User Admin Connecté     
            return $this->render('users/index.html.twig', [
                'users' => $userRepository->findAll(),
            ]);
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/new", name="app_users_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository, Tools $tools): Response
    {
        $roleuser = $tools->getUser()->getRoles()[0];

        if ($roleuser != null && $roleuser === "ROLE_ADMIN") {
            // User Admin Connecté     
            $user = new User();
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $userRepository->add($user, true);

                return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('users/new.html.twig', [
                'user' => $user,
                'form' => $form,
            ]);
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_users_show", methods={"GET"})
     */
    public function show(User $user, Tools $tools): Response
    {
        $roleuser = $tools->getUser()->getRoles()[0];

        if ($roleuser != null && $roleuser === "ROLE_ADMIN") {
            // User Admin Connecté    
            return $this->render('users/show.html.twig', [
                'user' => $user,
            ]);
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/edit", name="app_users_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository, Tools $tools): Response
    {

        $roleuser = $tools->getUser()->getRoles()[0];

        if ($roleuser != null && $roleuser === "ROLE_ADMIN") {
            // User Admin Connecté    

            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $userRepository->add($user, true);

                return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('users/edit.html.twig', [
                'user' => $user,
                'form' => $form,
            ]);
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_users_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository, Tools $tools): Response
    {
        $roleuser = $tools->getUser()->getRoles()[0];

        if ($roleuser != null && $roleuser === "ROLE_ADMIN") {
            // User Admin Connecté    
            if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
                $userRepository->remove($user, true);
            }
            return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }
}
