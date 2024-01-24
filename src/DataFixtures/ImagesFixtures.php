<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Images;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

// On implémente l'interface DependentFixtureInterface pour pouvoir utiliser 
// les références (getReferences()) car on en a besoin dans cette fixture pour récupérer 
// les produits (Image est lié à Product, et on a besoin de récupérer les produits 
// pour pouvoir les lier aux images), Lors du D:f:l, les fixtures sont chargées dans
// l'ordre suivant: CategoriesFixtures, ProductsFixtures, ImagesFixtures

class ImagesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($img = 1; $img <= 200; $img++) {
            $image = new Images();
            $image->setName($faker->imageUrl(640, 480, 'plants'));          
            $product = $this->getReference('prod-'.rand(1, 60));
            $image->setProducts($product);
            $manager->persist($image);
        }

        $manager->flush();
    }

    // On ajoute la méthode getDependencies() pour indiquer les dépendances de cette fixture, 
    // c.a.d les fixtures dont on a besoin pour que celle-ci fonctionne (décharger ProductsFixtures avant ImagesFixtures)
    public function getDependencies() : array
    {
        return [
            ProductsFixtures::class,
        ];
    }
}
