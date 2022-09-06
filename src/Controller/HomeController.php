<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Games;
use App\Services\Tools;
use App\Repository\SwapRepository;
use App\Repository\UserRepository;
use App\Repository\GamesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(CategoriesRepository $categoriesRepository, GamesRepository $gamesRepository, SwapRepository $swapRepository, Tools $tools, UserRepository $userRepository): Response
    {


        $user = $tools->getUser();

        if ($user != null) {
            $iduser = $user->getId();
            // $games = $gamesRepository->findGameByNotUser($iduser);
            $games = $gamesRepository->findAll();
        } else {
            $iduser = 0;
            $games = $gamesRepository->findAll();
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

                dump(count($sw), $sell);

                for ($k = 0; $k < count($sw); $k++) {

                    $idseller = $sw[$k]->getiduser()->getid();

                    if ($idseller != $iduser) {
                        array_push($sell, $idseller);
                        $gamesell[$idgame] = $sell;
                    }
                    // dump($k, $idseller, $iduser, $sell);
                    
                }

                // dd('stop');                


                array_push($dispo, $sw);
            } else {
                $sw = "";
                array_push($dispo, $sw);
            }


            // remplace la valeur dans $input à l'index $x
            // pour les tableaux dont les clés sont égales à l'offset
            // $input[$x] = $y; 
            // array_splice($input, $x, 1, $y);

            // $idshape = $swap[$i]->getidshape()->getid();
            // array_push($etat, $shapeRepository->find($idshape)->getEtat());
            // $games->append($gamesRepository->findGameByUser($id));
        }

        // dd($gamesell);
        // $swap = $swapRepository->findByUser($game);
// dd($gamesell);
        return $this->render('home/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'games' => $games,
            'dispos' => $dispo,
            'seller' => $gamesell,
            'user' => $alluser,
        ]);
    }
}
