<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;



class UsersFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    ) {
    }


    public function load(ObjectManager $manager): void
    {

        // Création d'un Admin User
        $admin = new Users();
        $admin->setEmail ('sylvaincadoret@hotmail.com');
        $admin->setLastname('Cadoret');
        $admin->setFirstname('Sylvain');
        $admin->SetAddress('20 chemin des bourgognes');
        $admin->setZipcode('95000');
        $admin->setCity('Pontoise');
        $admin->setPassword($this->passwordEncoder->hashPassword($admin, 'admin'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

// Création de Users via Faker

$faker = Faker\Factory::create('fr_FR');

for ($usr = 1; $usr <= 50; $usr++) {

    $user = new Users();
    $user->setEmail ($faker->email);
    $user->setLastname($faker->lastName);
    $user->setFirstname($faker->firstName);
    $user->SetAddress($faker->streetAddress);
    $user->setZipcode(str_replace(' ','',$faker->postcode));
    $user->setCity($faker->city);
    $user->setPassword($this->passwordEncoder->hashPassword($user, 'secret'));
    $manager->persist($user);

}
$manager->flush();
}
}
