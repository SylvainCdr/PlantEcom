<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/produits', name: 'app_products_')]
class ProductsController extends AbstractController
{
    #[Route('/plantes', name: 'plants')]
    public function plants(CategoriesRepository $categoriesRepository, ProductsRepository $productsRepository): Response
    {
        return $this->render('products/plants.html.twig', [
            'categories' => $categoriesRepository->findBy(
                [],
                ['categoryOrder' => 'ASC']
            ),
            'produits' => $productsRepository->findAll(),

        ]);
    }

#[Route('/all_plantes', name: 'allplants')]
public function allPlants(CategoriesRepository $categoriesRepository, ProductsRepository $productsRepository): Response
{
   return $this->render('products/allplants.html.twig', [

    // on appel la méthode findPlants() de notre repository pour récupérer les produits des catégories affilié à la catégorie parente plante
    'plants' => $productsRepository->findPlants(),
    'categories' => $categoriesRepository->findBy(
        [],
        ['categoryOrder' => 'ASC']
    ),


   ]);
}


    #[Route('/accessoires', name: 'accessories')]
    public function accessories(CategoriesRepository $categoriesRepository, ProductsRepository $productsRepository): Response
    {
        return $this->render('products/accessories.html.twig', [
            'categories' => $categoriesRepository->findBy(
                [],
                ['categoryOrder' => 'ASC']
            ),
            'produits' => $productsRepository->findAll(),

        ]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(Products $product): Response
    {
        // dd($product->getName());
        return $this->render('products/details.html.twig', [
            'controller_name' => 'Fiche produit',
            'product' => $product
        ]);
    }
}
