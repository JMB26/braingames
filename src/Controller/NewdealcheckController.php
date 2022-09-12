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
                $sent = "";

                $check = htmlspecialchars($_POST['check']);
                if ($check === 'Accepter') {
                    // Echange Accepté...

                    $idsel = htmlspecialchars($_POST['seller']);
                    $idbuy = htmlspecialchars($_POST['buyer']);

                    $swapsell = $swapRepository->find($idsel);
                    $swapbuy = $swapRepository->find($idbuy);

                    $swapbuy->setSwapbuyer(true);
                    $swapRepository->add($swapbuy, true);

                    $swapsell->setSwapuser(true);
                    $swapRepository->add($swapsell, true);

                    $sent = 3;
                } else {
                    if ($check === 'Refuser') {
                        // Echange Refusé... 
                        $idsel = htmlspecialchars($_POST['seller']);
                        $idbuy = htmlspecialchars($_POST['buyer']);

                        $swapsell = $swapRepository->find($idsel);
                        $swapbuy = $swapRepository->find($idbuy);

                        $swsu = $swapsell->isSwapuser();
                        $swsb = $swapsell->isSwapbuyer();
                        $swbu = $swapbuy->isSwapuser();
                        $swbb = $swapbuy->isSwapbuyer();

                        if ($swsu == false || $swsb == false || $swbu == false || $swbb == false) {
                            // Annulation d'un échange si non validé                    

                            $idsel = htmlspecialchars($_POST['seller']);
                            $idbuy = htmlspecialchars($_POST['buyer']);

                            $swapsell = $swapRepository->find($idsel);
                            $swapbuy = $swapRepository->find($idbuy);

                            $swapbuy->setSwapuser(false);
                            $swapbuy->setSwapbuyer(false);
                            $swapbuy->setIdswapbuyer(null);
                            $swapbuy->setIdbuyer(null);
                            $swapRepository->add($swapbuy, true);

                            $swapsell->setSwapuser(false);
                            $swapsell->setSwapbuyer(false);
                            $swapsell->setIdswapbuyer(null);
                            $swapsell->setIdbuyer(null);
                            $swapRepository->add($swapsell, true);
                            $sent = 4;                            
                        }
                    } else {                        
                        if ($check === 'Valider') {
                            // Echange Validé...
                            $idsel = htmlspecialchars($_POST['seller']);
                            $idbuy = htmlspecialchars($_POST['buyer']);

                            $swapsell = $swapRepository->find($idsel);
                            $swapbuy = $swapRepository->find($idbuy);

                            $swapbuy->setSwapuser(true);
                            $swapRepository->add($swapbuy, true);

                            $swapsell->setSwapbuyer(true);
                            $swapRepository->add($swapsell, true);
                            $sent = 5;
                        }
                    }
                }
                // TODO -> envoyer le mail de reponse 
                return $this->render('newdealprop/index.html.twig', [
                    'sent' => $sent,
                ]);
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
