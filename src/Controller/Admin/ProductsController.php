<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Repository\ProductsRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/produits', name: 'admin_products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $productRepository): Response
    {

$products = $productRepository->findAll();
        return $this->render('admin/products/index.html.twig', [
            'produits' => $products
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
    public function delete(Products $product, EntityManagerInterface $em): Response
    {

        //On vérifie si l'utilisateur peut supprimer avec le Voter
        // $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
        // return $this->render('admin/products/index.html.twig');

       
        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'Le produit a bien été supprimé');

        return $this->redirectToRoute('admin_products_index');
    }







    #[Route('/suppression/image/{id}', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(Images $image, Request $request, EntityManagerInterface $em, PictureService $pictureService): JsonResponse
    {

          //On vérifie si l'utilisateur peut supprimer avec le Voter
        // $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
        // return $this->render('admin/products/index.html.twig');

        //on récupère le contenu de la requête
$data = json_decode($request->getContent(), true);

if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
// le token est valide 
// On récupère le nom de l'image
$nom = $image->getName();

if($pictureService->delete($nom, 'products', 300, 300)){
    //On supprime l'image de la base de données
    $em->remove($image);
    $em->flush();
   
    return new JsonResponse(['success' => true], 200);
}

// la suppression a échoué
return new JsonResponse(['error' => 'Erreur de suppression'], 400);



}
return new JsonResponse(['error' => 'Token invalide'], 400);
    
}

    }
