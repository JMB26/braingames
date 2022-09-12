<?php

namespace App\Controller;

use App\Services\Tools;
use App\Repository\SwapRepository;
use App\Repository\UserRepository;
use App\Repository\GamesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie/{cat}", name="app_categorie", defaults={"cat": null})
     */
    public function index($cat, CategoriesRepository $categoriesRepository, GamesRepository $gamesRepository, SwapRepository $swapRepository, Tools $tools, UserRepository $userRepository): Response
    {



        if ($cat == null) {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $user = $tools->getUser();

        $categ = $categoriesRepository->findByCatNom($cat);
        
        if ($user != null) {
            $iduser = $user->getId();
            // $games = $gamesRepository->findGameByCategNotUser($iduser,$cat);
            if ($categ != null) {           
                $games = $gamesRepository->findGameByCateg($categ[0]['id']);            
            } else {
                $games = [];
            }
        } else {
            $iduser = 0;
            if ($categ != null) {           
                $games = $gamesRepository->findGameByCateg($categ[0]['id']);            
            } else {
                $games = [];
            }
        }

        $alluser = $userRepository->findAll();

        $dispo = [];
        $gamesell = [];
        $sell = [];

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

        // $games = [];
        return $this->render('categorie/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'games' => $games,
            'dispos' => $dispo,
            'seller' => $gamesell,
            'user' => $alluser,
            'categ' => $cat,

        ]);
    }
}
