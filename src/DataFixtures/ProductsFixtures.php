<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Products;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class ProductsFixtures extends Fixture

{

     // On ajoute le constructeur pour utiliser le slugger dans la fixture
     public function __construct(private SluggerInterface $slugger)
     {
     }


    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        
        for($prod = 1; $prod <= 60; $prod++)
        {
            $product = new Products();
            $product->setName($faker->text(15));
            $product->setDescription($faker->text(150));
            $product->setSlug($this->slugger->slug($product->getName())->lower());
            $product->setPrice($faker->numberBetween(9, 200));
            $product->setStock($faker->numberBetween(0, 12));
            
            
            // On va chercher une reference de categorie de façon aléatoire
            $category = $this->getReference('cat-'.rand(1, 19));
            // On ajoute la categorie au produit
            $product->setCategories($category);


            // On ajoute une reference au produit pour pouvoir l'utiliser dans la fixture ImagesFixtures
            $this->addReference('prod-'.$prod, $product);
            
            $manager->persist($product);

        }

        $manager->flush();
    }
  
}
