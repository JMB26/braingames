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

class NewdealcheckController extends AbstractController
{
    /**
     * @Route("/newdealcheck", name="app_newdealcheck")
     */
    public function index(Tools $tools, GamesRepository $gamesRepository, ShapeRepository $shapeRepository, SwapRepository $swapRepository, UserRepository $userRepository): Response
    {


        // dd($_POST);

        $user = $tools->getUser();

        if ($user != null) {
            // User connecté...
            if (isset($_POST['check'])) {
                // Validation échange ou pas
                $check = htmlspecialchars($_POST['check']);
                if ($check === 'Accepter') {
                    dd("echange ok");
                } else {
                    // Refuser l'echange...
                    dd("echange ko");
                }
                // TODO -> envoyer le mail de reponse 

                
            } else {
                // Affichage proposition
                if (isset($_POST['seller'])) {
                    $idsel = htmlspecialchars($_POST['seller']);
                    $idbuy = htmlspecialchars($_POST['buyer']);

                    $swapsell = $swapRepository->find($idsel);
                    $swapbuy = $swapRepository->find($idbuy);

                    $sel =  $userRepository->find($swapsell->getIduser()->getid());
                    $seladr = $sel->getcpost() . " " . $sel->getville();

                    $idgamesel = $swapsell->getIdgameuser()->getId();
                    $gamesel = $gamesRepository->find($idgamesel);
                    $etatgamesel = $swapsell->getidshape()->getetat();

                    $idgamebuy = $swapbuy->getIdgameuser()->getId();
                    $gamebuy = $gamesRepository->find($idgamebuy);
                    $etatgamebuy = $swapbuy->getidshape()->getetat();

                    $buy =  $userRepository->find($swapbuy->getIduser()->getid());
                    $buyadr = $buy->getcpost() . " " . $buy->getville();

                    // dd($etatgamesel);           

                    return $this->render('newdealcheck/index.html.twig', [
                        'swapsell' => $swapsell,
                        'swapbuy' => $swapbuy,
                        'gamesell' => $gamesel,
                        'gamebuy' => $gamebuy,
                        'etatsell' => $etatgamesel,
                        'etatbuy' => $etatgamebuy,
                        'adrsel' => $seladr,
                        'adrbuy' => $buyadr,
                    ]);
                } else {
                    // User non Connecté...
                    return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
                }
            }
        } else {
            // User non Connecté...
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
    }
}
