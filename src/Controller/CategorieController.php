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

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie/{cat}/{ipag}", name="app_categorie", defaults={"cat": null,"ipag": null})
     */
    public function index($ipag,$cat,CategoriesRepository $categoriesRepository, GamesRepository $gamesRepository, SwapRepository $swapRepository, Tools $tools, UserRepository $userRepository): Response
    {


        if ($cat == null) {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $user = $tools->getUser();

       
        // pagination
        if ($ipag == null) {
            $ipag = 1;
        }

        if ($user != null) {
            $iduser = $user->getId();            
            $pag = intval($gamesRepository->findGameCountCatUser($iduser,$cat)[0][1] / 5) + 1;
        } else {
            $iduser = 0;
           
            $pag = intval($gamesRepository->findGameCatCount($cat)[0][1] / 5) + 1;
        }
                
        if ($ipag > $pag) {
            $ipag = $pag;
        }

        $pagdeb = 20 * intval(($ipag - 1) / 20) + 1;
        $offset = ($ipag - 1) * 5;

        if ($user != null) {
            $games = $gamesRepository->findGameCatByNotUser($iduser,$cat, $offset);
        } else {

            $offset = ($ipag - 1) * 5;
            $games = $gamesRepository->findGameByCateg($cat,$offset);           
        }
        
        $countgames = count($games);


        $categ = $categoriesRepository->findByCatNom($cat);

      
        $alluser = $userRepository->findAll();

        $dispo = [];
        $gamesell = [];
        $sell = [];        

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
       
        return $this->render('categorie/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'games' => $games,
            'dispos' => $dispo,
            'seller' => $gamesell,
            'user' => $alluser,
            'categ' => $cat,
            'page' => $pag,
            'ipage' => $ipag,
            'pagedeb' => $pagdeb,
        ]);
    }

    public function navcateg(CategoriesRepository $categoriesRepository)
    {       
        return $this->render('include/navcateg.html.twig', [
            'navcat' => $categoriesRepository->findAllOrderByNom(),
        ]);
    }
}
