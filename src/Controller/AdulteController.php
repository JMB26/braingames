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

class AdulteController extends AbstractController
{
    /**
     * @Route("/adulte/{ipag}", name="app_adulte", defaults={"ipag": null})
     */
    public function index($ipag,CategoriesRepository $categoriesRepository, GamesRepository $gamesRepository, SwapRepository $swapRepository, Tools $tools, UserRepository $userRepository): Response
    {        
        // pagination
        // if ($ipag == null) {
        //     $ipag = 1;
        // }   
        
        // $pag = intval($gamesRepository->findGameAdultCount()[0][1] / 5) + 1;

        // if ($ipag > $pag) {
        //     $ipag = $pag;            
        // }    
        // $pagdeb = 20 * intval(($ipag-1)/20)+1;

        // $offset = ($ipag-1)*5;  

        // $user = $tools->getUser();

        // if ($user != null) {
        //     $iduser = $user->getId();          
        //     $games = $gamesRepository-> findGameByAdult($offset);                
        // } else {
        //     $iduser = 0;
        //     $games = $gamesRepository->findGameByAdult($offset);
        // }
                  
        // $countgames = count($games);       

        // if ($ipag == null) {
        //     $ipag = 1;
        // }   
        // $pag = intval(($countgames/5)+1);

        // if ($ipag > $pag) {
        //     $ipag = $pag;
        // } 
        
        
        $user = $tools->getUser();

        // pagination
        if ($ipag == null) {
            $ipag = 1;            
        }   
        
        if ($user != null) {
            $iduser = $user->getId();
            $pag = intval($gamesRepository->findGameCountAdultUser($iduser)[0][1] / 5) + 1; 
        }else{
            $iduser = 0;   
            $pag = intval($gamesRepository->findGameAdultCount()[0][1] / 5) + 1;       
        }
     
        if ($ipag > $pag) {
            $ipag = $pag;
        }    
        $pagdeb = 20 * intval(($ipag-1)/20)+1;
        $offset = ($ipag-1)*5;
       
        if ($user != null) {            
            // $games = $gamesRepository->findGameByNotUser($iduser,$offset); 
            $games = $gamesRepository->findGameAdultByNotUser($iduser,$offset);   
        } else {
                    
            $offset = ($ipag-1)*5;           
            $games = $gamesRepository->findGameByAdult($offset);
            // $games = $gamesRepository->findGameByNotUser($iduser,$offset);             
        }

        // dd($games);
        $countgames = count($games);

        $alluser = $userRepository->findAll();

        $dispo = [];
        $gamesell = [];
        $sell = [];
       
        // dd($countgames);

        // dump($gamesell,$sell);
        for ($i = 0; $i < $countgames; $i++) {
            // dd($games);
            $idgame = $games[$i];
            // dd($idgame);
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
       
        return $this->render('adulte/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'games' => $games,
            'dispos' => $dispo,
            'seller' => $gamesell,
            'user' => $alluser,  
            'page' => $pag,
            'ipage' => $ipag, 
            'pagedeb' => $pagdeb,           
        ]);       
    }
}
