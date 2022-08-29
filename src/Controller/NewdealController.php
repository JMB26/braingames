<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewdealController extends AbstractController
{
    /**
     * @Route("/newdeal", name="app_newdeal")
     */
    public function index(): Response
    {
        return $this->render('newdeal/index.html.twig', [
            'controller_name' => 'NewdealController',
        ]);
    }
}
