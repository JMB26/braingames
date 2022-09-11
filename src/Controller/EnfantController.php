<?php

namespace App\Controller;

use App\Services\Tools;
use App\Repository\SwapRepository;
use App\Repository\UserRepository;
use App\Repository\GamesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EnfantController extends AbstractController
{
    /**
     * @Route("/enfant", name="app_enfant")
     */
    public function index(CategoriesRepository $categoriesRepository, GamesRepository $gamesRepository, SwapRepository $swapRepository, Tools $tools, UserRepository $userRepository): Response
    {

        $user = $tools->getUser();

        if ($user != null) {
            $iduser = $user->getId();
            // $games = $gamesRepository->findGameByNotUser($iduser);
            $games = $gamesRepository-> findGameByChild();                
        } else {
            $iduser = 0;
            $games = $gamesRepository->findGameByChild();
        }

        $alluser = $userRepository->findAll();

        $dispo = [];
        $gamesell = [];
        $sell = [];
       

        // dump($gamesell,$sell);
        for ($i = 0; $i < count($games); $i++) {
            $idgame = $games[$i]->getid();
            $sw = $swapRepository->findByGame($idgame);

            if (!empty($sw)) {
                $idgame = $sw['0']->getidgameuser()->getid();

                if (array_key_exists($idgame, $gamesell)) {
                    $sell = $gamesell[$idgame];
                } else {
                    $sell = [];
                }                

                for ($k = 0; $k < count($sw); $k++) {
                    $idseller = $sw[$k]->getiduser()->getid();

                    if ($idseller != $iduser) {
                        array_push($sell, $idseller);
                        $gamesell[$idgame] = $sell;
                    }    
                }

                array_push($dispo, $sw);
            } else {
                $sw = "";
                array_push($dispo, $sw);
            }        
        }
       
        return $this->render('enfant/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'games' => $games,
            'dispos' => $dispo,
            'seller' => $gamesell,
            'user' => $alluser,            
        ]);       
    }
}
