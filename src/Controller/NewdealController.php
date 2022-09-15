<?php

namespace App\Controller;

use App\Entity\User;

use App\Services\Tools;
use App\Form\NewdealType;
use Symfony\Component\Mime\Email;
use App\Repository\SwapRepository;
use App\Repository\UserRepository;
use App\Repository\GamesRepository;
use App\Repository\ShapeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class NewdealController extends AbstractController
{
    /**
     * @Route("/newdeal", name="app_newdeal")
     */
    public function index(Request $request, MailerInterface $mailer, GamesRepository $gamesRepository, SwapRepository $swapRepository, ShapeRepository $shapeRepository, Tools $tools, UserRepository $userRepository): Response
    {

        // dd($_POST);
        $user = $tools->getUser();

        if ($user != null) {
            // User Connecté      

            $form = $this->createForm(NewdealType::class);

            $form->handleRequest($request);

            if ($request->isMethod('POST')) {

                if (isset($_POST['seller'])) {
                    if (isset($_POST['idbuy'])) {

                        // if ($form->isSubmitted() && $form->isValid()) {
                        $dataform = $form->getdata();
                        // dd($dataform);
                        // $email = $dataform['email'];
                        $email = 'jmb-dw26@outlook.fr';
                        // $content = $dataform['content'];
                        $content = 'Super Cool';

                        $email = (new Email())
                            ->from($email)
                            ->to($email)
                            ->subject('A propos de votre jeu...')
                            ->text('Bonjour, SLorem ipsum dolor sit amet consectetur adipisicing elit. Sint est enim qui quisquam in minus vel nam perspiciatis incidunt eum quae,');

                        $sent = 1;
                        try {
                            $mailer->send($email);

                            $idswapsel = htmlspecialchars($_POST['seller']);
                            $idswapbuy = htmlspecialchars($_POST['idbuy']);

                            $swapsell = $swapRepository->find($idswapsel);
                            $swapbuy = $swapRepository->find($idswapbuy);                           

                            $swapsell->setIdbuyer($user);
                            $swapsell->setIdswapbuyer($idswapbuy);  
                            $swapRepository->add($swapsell, true);

                            $swapbuy->setIdswapbuyer($idswapsel);

                            $swapRepository->add($swapbuy, true);  
                           
                        } catch (TransportExceptionInterface $e) {
                            $sent = 0;
                        }

                        return $this->render('newdealprop/index.html.twig', [
                            'sent' => $sent,
                        ]);
                        dd($mailer);
                    } else {
                        $sent = 0;
                        return $this->render('newdealprop/index.html.twig', [
                            'sent' => $sent,
                        ]);
                    }
                } else {

                    if (isset($_POST['buy'])) {

                        $idswapbuy = htmlspecialchars($_POST['buy']);

                        $swapbuy = $swapRepository->find($idswapbuy);
                        $idgamebuy = $swapbuy->getIdgameuser()->getId();
                        $gamebuy = $gamesRepository->find($idgamebuy);
                        $etatgamebuy = $swapbuy->getidshape()->getetat();

                        $idswapsel = htmlspecialchars($_POST['sell']);
                        $swapsell = $swapRepository->find($idswapsel);
                        $etatgamesell = $swapsell->getidshape()->getetat();
                        $idgamesel = $swapsell->getIdgameuser()->getId();
                        $gamesel = $gamesRepository->find($idgamesel);
                      
                        return $this->renderform('newdeal/index.html.twig', [
                            'etatgamesell' => $etatgamesell,
                            'etatgamebuy' => $etatgamebuy,
                            'gamesell' => $gamesel,
                            'gamebuy' => $gamebuy,
                            'swapsell' => $swapsell,
                            'idswapbuy' => $idswapbuy,
                            'newdealform' => $form,
                        ]);
                    } else {                       
                        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
                    }
                }
            }
        } else {
            // User non Connecté...
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
    }
}
