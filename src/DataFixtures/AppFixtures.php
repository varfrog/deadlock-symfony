<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $userAlice = new User('Alice');
        $userBob = new User('Bob');

        $manager->persist($userAlice);
        $manager->persist($userBob);

        $manager->flush();
    }
}
