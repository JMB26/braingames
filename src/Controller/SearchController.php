<?php

namespace App\Controller;

use App\Repository\GamesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="app_search")
     */
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('search/index.html.twig', [
            'categories' => $categoriesRepository->findAll(), 
        ]);
    }

    // Ft recherche de la navbar

    public function searchBar(CategoriesRepository $categoriesRepository)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre Recherche'
                ]
            ])           
            ->getForm();
        return $this->render('search/searchBar.html.twig', [
            'categories' => $categoriesRepository->findAll(), 
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/handleSearch", name="handleSearch")
     * @param Request $request
     */
    public function handleSearch(Request $request, GamesRepository $repo,CategoriesRepository $categoriesRepository)
    {
        $query = $request->request->get('form')['query'];
        if($query) {
            $games = $repo->findGamesByName($query);
        }

        return $this->render('search/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'games' => $games
        ]);
    }


}
