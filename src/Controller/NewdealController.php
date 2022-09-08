<?php

namespace App\Controller;

use App\Entity\User;

use App\Services\Tools;
use App\Repository\SwapRepository;
use App\Repository\GamesRepository;
use App\Repository\ShapeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewdealController extends AbstractController
{
    /**
     * @Route("/newdeal", name="app_newdeal")
     */
    public function index(GamesRepository $gamesRepository, SwapRepository $swapRepository, ShapeRepository $shapeRepository, Tools $tools): Response
    {

        $user = $tools->getUser();

        if ($user != null) {
            // $idgamebuy = htmlspecialchars($_POST['gamebuy']);


            $idswapbuy = htmlspecialchars($_POST['buy']);  

            $swapbuy = $swapRepository->find($idswapbuy);            
            $idgamebuy = $swapbuy->getIdgameuser()->getId();
            $gamebuy = $gamesRepository->find($idgamebuy);
            $etatgamebuy = $swapbuy->getidshape()->getetat();
          
            $idswapsel = htmlspecialchars($_POST['sell']);
            $swapsell = $swapRepository->find($idswapsel);  
            $etatgamesell = $swapsell->getidshape()->getetat(); 
            $idgamesel = $swapsell->getIdgameuser()->getId(); 
            $gamesel= $gamesRepository->find($idgamesel);
            
            return $this->render('newdeal/index.html.twig', [
                // 'swapsell' => $swapsell,
                // 'swapbuy' => $swapbuy,
                'etatgamesell' => $etatgamesell,
                'etatgamebuy' => $etatgamebuy,
                'gamesell' => $gamesel,
                'gamebuy' => $gamebuy,
            ]);
        } else {
            // User non Connecté...
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
    }
}
