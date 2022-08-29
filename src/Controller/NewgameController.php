<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewgameController extends AbstractController
{
    /**
     * @Route("/newgame", name="app_newgame")
     */
    public function index(): Response
    {
        return $this->render('newgame/index.html.twig', [
            'controller_name' => 'NewgameController',
        ]);
    }
}
