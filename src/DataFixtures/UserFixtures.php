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

        $user = new User;
        $user->setPseudo('Claire');
        $user->setEmail('clr.minier@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'azerty'
        );
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $user2 = new User;
        $user2->setPseudo('Robert');
        $user2->setEmail('robert@gmail.com');
        $user2->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user2,
            'azerty'
        );
        $user2->setPassword($hashedPassword);
        $manager->persist($user2);

        $user3 = new User;
        $user3->setPseudo('Jacques');
        $user3->setEmail('jacques@gmail.com');
        $user3->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user3,
            'azerty'
        );
        $user3->setPassword($hashedPassword);
        $manager->persist($user3);

        $user4 = new User;
        $user4->setPseudo('Michel');
        $user4->setEmail('michel@gmail.com');
        $user4->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user2,
            'azerty'
        );
        $user4->setPassword($hashedPassword);
        $manager->persist($user4);

        $user5 = new User;
        $user5->setPseudo('Guillaume');
        $user5->setEmail('guillaume@gmail.com');
        $user5->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user5,
            'azerty'
        );
        $user5->setPassword($hashedPassword);
        $manager->persist($user5);

        $manager->flush();
    }
}
