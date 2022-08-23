<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdulteController extends AbstractController
{
    /**
     * @Route("/adulte", name="app_adulte")
     */
    public function index(): Response
    {
        return $this->render('adulte/index.html.twig', [
            'controller_name' => 'AdulteController',
        ]);
    }
}
