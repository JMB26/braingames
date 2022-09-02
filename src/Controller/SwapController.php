<?php

namespace App\Controller;

use App\Entity\Shape;
use App\Entity\Swap;

use App\Form\SwapType;
use App\Services\Tools;
use App\Repository\SwapRepository;
use App\Repository\GamesRepository;
use App\Repository\ShapeRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/swap")
 */
class SwapController extends AbstractController
{
    /**
     * @Route("/", name="app_swap_index", methods={"GET"})
     */    
    public function index(SwapRepository $swapRepository): Response
    {
        return $this->render('swap/index.html.twig', [
            'swaps' => $swapRepository->findAll(),            
        ]);
    }
  
    /**
     * @Route("/new/{id}", name="app_swap_new", methods={"GET", "POST"})
     */
    public function new($id, Request $req, Request $request, SwapRepository $swapRepository,GamesRepository $gamesRepository,ShapeRepository $shapeRepository, Tools $tools, Shape $shape): Response
    {   
        $swap = new Swap();  
        $shape = new Shape();  
        

        $form = $this->createForm(SwapType::class, $swap);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
               
            // dd($id);

            $idshape  = $request->request->get('idshape'); 
            $shape = $shapeRepository->find($idshape);
            $swap -> setIdshape($shape); 

            $game = $gamesRepository->find($id);
            $swap -> setIdgameuser($game); 
            
            $swap -> setSwapuser(false); 
            $swap -> setSwapbuyer(false); 
                   
            // $user = $tools->getUser()->getId();
                  
            $user = $tools->getUser();
            $swap -> setIduser($user);
            
            $swap -> setIdbuyer(null);
            $swap -> setIdgamebuyer(null); 

            
            $swapRepository->add($swap, true);

            return $this->redirectToRoute('app_swap_index', [], Response::HTTP_SEE_OTHER);
        }
           
        dump("Don't Save");
        return $this->renderForm('swap/new.html.twig', [  
            'game' => $gamesRepository->find($id),   
            'shapes' => $shapeRepository->findAll(),                  
            'swap' => $swap,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_swap_show", methods={"GET"})
     */
    public function show(Swap $swap): Response
    {
        return $this->render('swap/show.html.twig', [
            'swap' => $swap,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_swap_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Swap $swap, SwapRepository $swapRepository): Response
    {
        $form = $this->createForm(SwapType::class, $swap);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $swapRepository->add($swap, true);

            return $this->redirectToRoute('app_swap_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('swap/edit.html.twig', [
            'swap' => $swap,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_swap_delete", methods={"POST"})
     */
    public function delete(Request $request, Swap $swap, SwapRepository $swapRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$swap->getId(), $request->request->get('_token'))) {
            $swapRepository->remove($swap, true);
        }

        return $this->redirectToRoute('app_swap_index', [], Response::HTTP_SEE_OTHER);
    }
}
