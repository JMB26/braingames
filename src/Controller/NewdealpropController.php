<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewdealpropController extends AbstractController
{
    /**
     * @Route("/newdealprop", name="app_newdealprop")
     */
    public function index(): Response
    {


        return $this->render('newdealprop/index.html.twig', [
            'sent' => '1',
        ]);
    }
}
