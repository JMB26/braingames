<?php

namespace App\Controller;

use App\Entity\Games;
use App\Repository\GamesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewgameController extends AbstractController
{
    /**
     * @Route("/newgame/{id}", name="app_newgame")
     */
    public function index($id,Games $games,GamesRepository $gamesRepository,CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('newgame/index.html.twig', [
            'game' => $gamesRepository->find($id),  
            'categories' => $categoriesRepository->findAll(),  
            'id' =>$games->getId()        
        ]);
    }
}
