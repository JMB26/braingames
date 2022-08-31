<?php

namespace App\Controller;

use App\Entity\Games;
use App\Form\GamesType;
use App\Form\GamesTypeMaj;
use App\Repository\GamesRepository;
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
    public function index(GamesRepository $gamesRepository): Response
    {
        return $this->render('games/index.html.twig', [
            'games' => $gamesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_games_new", methods={"GET", "POST"})
     */
    public function new(Request $request, GamesRepository $gamesRepository): Response
    {
        $game = new Games();
        $form = $this->createForm(GamesType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        // Ajout image
            
            $img = $form->get('img')->getData();
            
            if ($img) {
                // L'image Existe
                $new_name_img = uniqid() . '.'  . $img->guessExtension();
            
                $img->move($this->getParameter('upload_dir'), $new_name_img);            
                $game->setImg($new_name_img);
            } else {
                // Image par default 
                $game->setImg('defaultImg.png');
            }

            $gamesRepository->add($game, true);

            return $this->redirectToRoute('app_games_index', [], Response::HTTP_SEE_OTHER);
        }
   
        return $this->renderForm('games/new.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_games_show", methods={"GET"})
     */
    public function show(Games $game): Response
    {
            return $this->render('games/show.html.twig', [
            'game' => $game,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_games_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Games $game, GamesRepository $gamesRepository): Response
    {
        $form = $this->createForm(GamesTypeMaj::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gamesRepository->add($game, true);

            return $this->redirectToRoute('app_games_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('games/edit.html.twig', [           
            'game' => $game,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_games_delete", methods={"POST"})
     */
    public function delete(Request $request, Games $game, GamesRepository $gamesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $gamesRepository->remove($game, true);
        }

        return $this->redirectToRoute('app_games_index', [], Response::HTTP_SEE_OTHER);
    }
}
