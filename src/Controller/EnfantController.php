<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnfantController extends AbstractController
{
    /**
     * @Route("/enfant", name="app_enfant")
     */
    public function index(): Response
    {
        return $this->render('enfant/index.html.twig', [
            'controller_name' => 'EnfantController',
        ]);
    }
}
