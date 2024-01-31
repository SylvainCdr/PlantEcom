<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/produits', name: 'admin_products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/products/index.html.twig', [
            'controller_name' => 'Liste des produits',
        ]);
    }


    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //On créé un "nouveau produit"
        $product = new Products();

        //On crée le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        // On traite la requête du formulaire
        $productForm->handleRequest($request);

        // On vérifie si le formulaire est soumis et valide
        if ($productForm->isSubmitted() && $productForm->isValid()) {

            //On récupère les images transmises
            $images = $productForm->get('images')->getData();

           foreach ($images as $image) {
            // On définit le dossier de destination
            $folder = 'products';

            // On appelle le service d'ajout d'image
            $fichier = $pictureService->add($image, $folder, 300, 300);
           
            $img = new Images();
            $img->setName($fichier);
            $product->addImage($img);
           }

            // On génère le slug
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);

            // On enregistre le produit
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Le produit a bien été ajouté');

            // On redirige
            return $this->redirectToRoute('admin_products_index');
        }


        return $this->render(
            'admin/products/add.html.twig',
            [
                'productForm' => $productForm->createView()
            ]
        );
    }



    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Products $product, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {

        //On vérifie si l'utilisateur peut éditer avec le Voter
        // $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);
        // return $this->render('admin/products/index.html.twig');

        //On crée le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        // On traite la requête du formulaire
        $productForm->handleRequest($request);

        // On vérifie si le formulaire est soumis ET valide
        if ($productForm->isSubmitted() && $productForm->isValid()) {

              //On récupère les images transmises
              $images = $productForm->get('images')->getData();

              foreach ($images as $image) {
               // On définit le dossier de destination
               $folder = 'products';
   
               // On appelle le service d'ajout d'image
               $fichier = $pictureService->add($image, $folder, 300, 300);
              
               $img = new Images();
               $img->setName($fichier);
               $product->addImage($img);
              }

            // On génère le slug
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);

            // On enregistre le produit
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Le produit a bien été mofifié');

            // On redirige
            return $this->redirectToRoute('admin_products_index');
        }


        return $this->render(
            'admin/products/edit.html.twig',
            [
                'productForm' => $productForm->createView(),
                'product' => $product
            ]
        );
    }



    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Products $product): Response
    {
        return $this->render('admin/products/index.html.twig');
    }
}