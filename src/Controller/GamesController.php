<?php

namespace App\Controller;

use App\Entity\Games;
use App\Form\GamesType;
use App\Services\Tools;
use App\Form\GamesTypeMaj;
use App\Repository\GamesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/games")
 */
class GamesController extends AbstractController
{
    /**
     * @Route("/", name="app_games_index", methods={"GET"})
     */
    public function index(GamesRepository $gamesRepository, Tools $tools): Response
    {

        $roleuser = $tools->getUser()->getRoles()[0];

        if ($roleuser != null && $roleuser === "ROLE_ADMIN") {

            return $this->render('games/index.html.twig', [
                'games' => $gamesRepository->findAll(),
            ]);
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/new", name="app_games_new", methods={"GET", "POST"})
     */
    public function new(Request $request, GamesRepository $gamesRepository,CategoriesRepository $categoriesRepository,Tools $tools): Response
    {

        $user = $tools->getUser();

        if ($user != null) {
            // User Connecté   
            $game = new Games();

            $form = $this->createForm(GamesType::class, $game);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Ajout image

                $img = $form->get('img')->getData();

                if ($img) {
                    // L'image Existe
                    $new_img = uniqid() . '.'  . $img->guessExtension();

                    $img->move($this->getParameter('upload_dir'), $new_img);
                    $game->setImg($new_img);
                } else {
                    // Image par default 
                    $game->setImg('defaultImg.jpg');
                }

                // A setter : 
                // - note 
                // - Status
                // - Date

                $gamesRepository->add($game, true);

                return $this->redirectToRoute('app_games_index', [], Response::HTTP_SEE_OTHER);
            }

            // dd($game);
            return $this->renderForm('games/new.html.twig', [
                'categories' => $categoriesRepository->findAllOrderByNom(),
                'game' => $game,
                'form' => $form,               
            ]);
        } else {
            // User non Connecté...
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_games_show", methods={"GET"})
     */
    public function show(Games $game, Tools $tools): Response
    {

        $roleuser = $tools->getUser()->getRoles()[0];

        if ($roleuser != null && $roleuser === "ROLE_ADMIN") {
            return $this->render('games/show.html.twig', [
                'game' => $game,
            ]);
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/edit", name="app_games_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Games $game, GamesRepository $gamesRepository, Tools $tools): Response
    {
        $roleuser = $tools->getUser()->getRoles()[0];

        if ($roleuser != null && $roleuser === "ROLE_ADMIN") {

            $old_name = $game->getImg();
            $form = $this->createForm(GamesTypeMaj::class, $game);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Image actuelle 
                $img = $form->get('img')->getData();
                if ($img) {
                    // Nom unique à la nouvelle image & Envoi -> serveur                 
                    $new_img = uniqid() . '.'  . $img->guessExtension();
                    $img->move($this->getParameter('upload_dir'), $new_img);

                    // Set imgje set l'objet article avec le nouveau nom
                    $game->setImg($new_img);

                    // supprimer l'ancienne image si elle existe
                    if (file_exists($old_name)) {
                        $path = $this->getParameter('upload_dir') . $old_name;
                        unlink($path);
                    }
                } else {
                    // si pas d'image dans le form   
                    if (strlen($old_name) < 1) {
                        // on set l'img par défaut                     
                        $game->setImg('defaultImg.jpg');
                    } else {
                        $game->setImg($old_name);
                    }
                }

                $gamesRepository->add($game, true);
                return $this->redirectToRoute('app_games_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('games/edit.html.twig', [
                'game' => $game,
                'form' => $form,
            ]);
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_games_delete", methods={"POST"})
     */
    public function delete(Request $request, Games $game, GamesRepository $gamesRepository, Tools $tools): Response
    {

        $roleuser = $tools->getUser()->getRoles()[0];

        if ($roleuser != null && $roleuser === "ROLE_ADMIN") {
            if ($this->isCsrfTokenValid('delete' . $game->getId(), $request->request->get('_token'))) {
                $gamesRepository->remove($game, true);
            }
            return $this->redirectToRoute('app_games_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }
}
