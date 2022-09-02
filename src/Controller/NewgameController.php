<?php

namespace App\Controller;

use App\Entity\Games;
use App\Repository\SwapRepository;
use App\Repository\GamesRepository;
use App\Repository\ShapeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewgameController extends AbstractController
{
    /**
     * @Route("/newgame/{id}", name="app_newgame")
     */
    public function index($id,Games $games,GamesRepository $gamesRepository,ShapeRepository $shapeRepository,SwapRepository $swapRepository): Response
    {
dd($swapRepository);

        return $this->render('swap/index.html.twig', [
            'swaps' => $swapRepository->findAll(),   
            'game' => $gamesRepository->find($id),  
            'shapes' => $shapeRepository->findAll(),  
            // 'id' =>$games->getId()        
        ]);

        // return $this->render('newgame/index.html.twig', [
        //     'game' => $gamesRepository->find($id),  
        //     'shapes' => $shapeRepository->findAll(),  
        //     'id' =>$games->getId()        
        // ]);
    }
}
