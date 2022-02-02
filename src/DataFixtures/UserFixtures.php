<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User;
        $admin->setPseudo('Mathias');
        $admin->setPicture('mathias.jpg');
        $admin->setEmail('marchand.mathias@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'bestfootever'
        );
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        $admin2 = new User;
        $admin2->setPseudo('Rémi');
        $admin2->setPicture('remi.jpg');
        $admin2->setEmail('r.guevel@hays.fr');
        $admin2->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin2,
            'hellofootball'
        );
        $admin2->setPassword($hashedPassword);
        $manager->persist($admin2);

        $manager->flush();
    }
}
