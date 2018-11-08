<?php

namespace App\DataFixtures;

use App\Entity\Trainer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 20; $i++) {
            $trainer = new Trainer();
            $trainer->setName('John Doe');
            $trainer->setPersonalStatement("personal statment");
            $trainer->setPhone("8686868686");
            $manager->persist($trainer);
        }
        $manager->flush();
    }
}
