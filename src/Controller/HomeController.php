<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Games;
use App\Services\Tools;
use App\Repository\SwapRepository;
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
    public function index(CategoriesRepository $categoriesRepository, GamesRepository $gamesRepository, SwapRepository $swapRepository, Tools $tools): Response
    {
        

        $user = $tools->getUser(); 
       
        if ($user != null) {
            $iduser = $user->getId(); 
        }   
                  


        // $games = $gamesRepository->findGameByNotUser($iduser);
        $games = $gamesRepository->findAll();

        
        $dispo = [];       
        for ($i = 0; $i < count($games); $i++) {
            $id = $games[$i]->getid();
            $sw = $swapRepository->findByGame($id);

            // dd($sw);




            if (!empty($sw)) {
                $id = $sw['0']->getidgameuser()->getid();
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
       
        // $swap = $swapRepository->findByUser($game);

        return $this->render('home/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            // 'games' => $gamesRepository->findAll(),
            // 'games' => $gamesRepository->findGameByNotUser($iduser),
            'games' => $gamesRepository->findAll(),
            'dispos' => $dispo,
        ]);
    }
}
