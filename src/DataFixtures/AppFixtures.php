<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = UserFactory::createOne([
            'roles' => [User::ROLE_ADMIN, User::ROLE_USER]
        ]);

        UserFactory::createMany(3);
        ProductFactory::createMany(10);

        $manager->flush();
    }
}
