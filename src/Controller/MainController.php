<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(CategoriesRepository $categoriesRepository, ProductsRepository $productsRepository): Response
    {

        return $this->render('main/index.html.twig', 
        [
            'categories' => $categoriesRepository->findBy([],
            ['categoryOrder' => 'ASC']),
            'produits' => $productsRepository->findAll(),
            
        ]);
    }



    // #[Route('/', name: 'app_main')]
    // public function products(ProductsRepository $productsRepository): Response
    // {

    //     return $this->render('main/index.html.twig', 
    //     [
    //         'products' => $productsRepository->findAll(),
    //     ]);
    // }
}



// [
//     'controller_name' => 'MainController',
   
// ]);