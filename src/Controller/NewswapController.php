<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewswapController extends AbstractController
{
    /**
     * @Route("/newswap", name="app_newswap")
     */
    public function index(): Response
    {
        return $this->render('newswap/index.html.twig', [
            'controller_name' => 'NewswapController',
        ]);
    }
}
