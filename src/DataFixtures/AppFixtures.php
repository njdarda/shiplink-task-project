<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'username' => 'admin',
            'roles' => [User::ROLE_ADMIN, User::ROLE_USER],
            'password' => $this->passwordHasher->hashPassword(new User(), 'admin_password')
        ]);

        UserFactory::createOne([
            'username' => 'user',
            'password' => $this->passwordHasher->hashPassword(new User(), 'user_password')
        ]);

        UserFactory::createMany(3);
        ProductFactory::createMany(10);

        $manager->flush();
    }
}
