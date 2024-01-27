<?php

namespace App\Controller;


use App\Entity\Categories;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/categories', name: 'categories_')]
class ProductsController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(Categories $category): Response
    {

        // On va chercher la liste des produits de la catégorie
        $products = $category->getProducts();
       

        return $this->render('categories/list.html.twig', [

            'category' => $category,
            'products' => $products
           
        ]);
    }
}
