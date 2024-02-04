<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categories', name: 'admin_categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findBy([], ['categoryOrder' => 'asc']);

        return $this->render('admin/categories/index.html.twig', compact('categories'));
    }

    #[Route('/ajout', name: 'add')]
    public function add( Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {

        $category = new Categories();

        $categoryForm = $this->createForm(CategoriesFormType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);

            $em->persist($category);
            $em->flush();


            $this->addFlash('success', 'La catégorie a bien été ajoutée');
            return $this->redirectToRoute('admin_categories_index');
        }


        return $this->render('admin/categories/add.html.twig',
            [
                'categoryForm' => $categoryForm->createView()
            ]
        );
    }

    #[Route('/modification/{id}', name: 'edit')]
    public function edit(Categories $category, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $categoryForm = $this->createForm(CategoriesFormType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);

            $em->flush();

            $this->addFlash('success', 'La catégorie a bien été modifiée');
            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/categories/edit.html.twig',
            [
                'categoryForm' => $categoryForm->createView()
            ]
        );
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Categories $category, EntityManagerInterface $em): Response
    {
        $em->remove($category);
        $em->flush();

        $this->addFlash('success', 'La catégorie a bien été supprimée');
        return $this->redirectToRoute('admin_categories_index');
    }


}