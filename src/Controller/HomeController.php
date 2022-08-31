<?php

namespace App\Controller;

use App\Entity\Games;
use App\Repository\GamesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index( CategoriesRepository $categoriesRepository,GamesRepository $gamesRepository): Response
    {
        

        return $this->render('home/index.html.twig', [
            'categories' => $categoriesRepository->findAll(), 
            'games' => $gamesRepository->findAll(),
        ]);
    }
}
