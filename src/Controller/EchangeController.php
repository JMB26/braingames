<?php

namespace App\Controller;


use ArrayObject;
use App\Services\Tools;
use App\Repository\SwapRepository;
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
    public function index(Tools $tools, GamesRepository $gamesRepository, ShapeRepository $shapeRepository, SwapRepository $swapRepository): Response
    {
        
        $user = $tools->getUser();

        if ($user != null) {
            // User connecté...
            $id = htmlspecialchars($_POST['seller']);
            $swapsell = $swapRepository->find($id);

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
            ]);
        } else {
            // User non Connecté...
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
    }
}
