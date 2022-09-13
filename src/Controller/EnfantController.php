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
     * @Route("/enfant/{ipag}", name="app_enfant", defaults={"ipag": null})
     */
    public function index($ipag,CategoriesRepository $categoriesRepository, GamesRepository $gamesRepository, SwapRepository $swapRepository, Tools $tools, UserRepository $userRepository): Response
    {

        if ($ipag == null) {
            $ipag = 1;
        }   
        
        $pag = intval($gamesRepository->findGameChildCount()[0][1] / 5) + 1;

        if ($ipag > $pag) {
            $ipag = $pag;
        }    
        $offset = ($ipag-1)*5;  
        
        $user = $tools->getUser();
        if ($user != null) {
            $iduser = $user->getId();
            // $games = $gamesRepository->findGameByNotUser($iduser);
            
            $games = $gamesRepository-> findGameByChild($offset);                
        } else {
            $iduser = 0;
            $games = $gamesRepository->findGameByChild($offset);
        }

        $countgames = count($games);       

        

        $alluser = $userRepository->findAll();

        $dispo = [];
        $gamesell = [];
        $sell = [];
       

        // dump($gamesell,$sell);
        for ($i = 0; $i < $countgames; $i++) {
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
            'page' => $pag,
            'ipage' => $ipag,             
        ]);       
    }
}
