<?php

namespace App\Controller;


use ArrayObject;

use App\Services\Tools;
use App\Repository\SwapRepository;
use App\Repository\UserRepository;
use App\Repository\GamesRepository;
use App\Repository\ShapeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EchangeController extends AbstractController
{
    /**
     * @Route("/echange", name="app_echange")
     */
    public function index(Tools $tools, GamesRepository $gamesRepository, ShapeRepository $shapeRepository, SwapRepository $swapRepository, UserRepository $userRepository): Response
    {
        
        $user = $tools->getUser();

        if ($user != null) {
            // User connecté...
            $id = htmlspecialchars($_POST['seller']);
            $swapsell = $swapRepository->find($id);
                     
            $idgame = $swapsell->getIdgameuser()->getId();
            $game = $gamesRepository->find($idgame);
            $etatgame = $swapsell->getidshape()->getetat();

            $selluser = $userRepository->find($swapsell->getIduser()->getId());         

            $games = new ArrayObject();
            $etat = [];          

            $swap = $swapRepository->findByUser($user);

            // $id = $swap['0']->getidgameuser()->getid();   

            for ($i = 0; $i < count($swap); $i++) {
                $id = $swap[$i]->getidgameuser()->getid();
                $idshape = $swap[$i]->getidshape()->getid();
                array_push($etat, $shapeRepository->find($idshape)->getEtat());
                $games->append($gamesRepository->findGameByUser($id));
            }
            
            return $this->render('echange/index.html.twig', [
                'swapsell' => $swapsell,
                'swapbuy' => $swapRepository->findByUser($user),
                'shapes' => $shapeRepository->findAll(),
                'games' => $games,
                'etats' => $etat,
                'etatgame' => $etatgame,               
                'gamesell' => $game,
                'selluser' => $selluser
            ]);
        } else {
            // User non Connecté...
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
    }
}
