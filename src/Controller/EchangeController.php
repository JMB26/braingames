<?php

namespace App\Controller;

use App\Repository\SwapRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EchangeController extends AbstractController
{
    /**
     * @Route("/echange", name="app_echange")
     */
    public function index(SwapRepository $swapRepository): Response
    {

        // $result = $_POST['seller'];
        
        $id = htmlspecialchars($_POST['seller']);
        // dd($id);
        $swap = $swapRepository->find($id);

        // dd($swap);
        return $this->render('echange/index.html.twig', [
            'swap' => $swap,
        ]);
    }
}
