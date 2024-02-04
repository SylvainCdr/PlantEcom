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
