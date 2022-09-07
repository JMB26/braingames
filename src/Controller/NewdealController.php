<?php
namespace App\Controller;

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
    public function index( GamesRepository $gamesRepository, SwapRepository $swapRepository, ShapeRepository $shapeRepository): Response 
    {
       
        
        $idgamebuy = htmlspecialchars($_POST['gamebuy']); 
        $idswapbuy = htmlspecialchars($_POST['buy']);
        $idswapsel = htmlspecialchars($_POST['sell']);

        $game = $gamesRepository->find($idgamebuy);
        dd($idswapsel,$idswapbuy,$idgamebuy,$game);

        
        $idswapsell = htmlspecialchars($_POST['sell']);        
        $swapsell = $swapRepository->find($idswapsell);
        $swapbuy = $swapRepository->find($idswapbuy);

        $etatgamesell = $swapsell->getidshape()->getetat();
        $etatgamebuy = $swapbuy->getidshape()->getetat();


       
       
        
        // $gamesell = $gamesRepository->find($idgamesel);
       

        

        return $this->render('newdeal/index.html.twig', [
            'swapsell' => $swapsell,
            'swapbuy' => $swapbuy,
            'etatgamesell' => $etatgamesell,
            'etatgamebuy' => $etatgamebuy,
        ]);
    }
}
