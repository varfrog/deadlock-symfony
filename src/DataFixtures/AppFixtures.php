<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const USERNAME_ALICE = 'alice';
    const USERNAME_BOB = 'bob';

    public function load(ObjectManager $manager)
    {
        $userAlice = new User(self::USERNAME_ALICE);
        $userBob = new User(self::USERNAME_BOB);

        $manager->persist($userAlice);
        $manager->persist($userBob);

        $manager->flush();
    }
}
