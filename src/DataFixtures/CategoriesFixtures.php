<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
// On ajoute une propriété privée pour compter les références
    private $counter = 1;
    // On ajoute le constructeur pour utiliser le slugger dans la fixture
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $parent = $this->createCategory('Plante', null, $manager);
    
        $this->createCategory(name:'Alocasia', parent: $parent, manager: $manager);
        $this->createCategory(name:'Epipremnum', parent: $parent, manager: $manager);
        $this->createCategory(name:'Monstera', parent: $parent, manager: $manager);
        $this->createCategory(name:'Philodendron', parent: $parent, manager: $manager);
        $this->createCategory(name:'Raphidophora', parent: $parent, manager: $manager);
        $this->createCategory(name:'Syngonium', parent: $parent, manager: $manager);
        $this->createCategory(name:'Begonia', parent: $parent, manager: $manager);
        $this->createCategory(name:'Grimpantes variées', parent: $parent, manager: $manager);
        $this->createCategory(name:'Autres', parent: $parent, manager: $manager);
     

        $parent = $this->createCategory('Matériel & Accessoires', null, $manager);


        $this->createCategory(name:'Pots', parent: $parent, manager: $manager);
        $this->createCategory(name:'Cache-pots', parent: $parent, manager: $manager);
        $this->createCategory(name:'Arrosoirs', parent: $parent, manager: $manager);
        $this->createCategory(name:'Pulvérisateurs', parent: $parent, manager: $manager);
        $this->createCategory(name:'Ciseaux', parent: $parent, manager: $manager);
        $this->createCategory(name:'Tuteurs', parent: $parent, manager: $manager);
        $this->createCategory(name:'Supports', parent: $parent, manager: $manager);
        $this->createCategory(name:'Sols', parent: $parent, manager: $manager);

        $manager->flush();
    }

    public function createCategory(string $name, ?Categories $parent = null, ObjectManager $manager)
    {
        $category = new Categories();

        // Remove the line below since the setId() method is not defined in the Categories entity class
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $manager->persist($category);

        // On ajoute une référence à chaque catégorie afin de pouvoir les récupérer dans les ProductsFixtures
        $this->addReference('cat-'.$this->counter, $category);
        $this->counter++;

        return $category;
    }
}
