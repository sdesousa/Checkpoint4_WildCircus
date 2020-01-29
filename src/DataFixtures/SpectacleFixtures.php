<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use App\Entity\Spectacle;

class SpectacleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 8; $i++) {
            $spectacle = new Spectacle();
            $spectacle->setName($faker->sentence(3));
            $spectacle->setPlace($faker->city);
            $spectacle->setCapacity(80);
            $spectacle->setDate($faker->dateTimeInInterval('now', '+3 month'));
            $spectacle->setPoster('circus-show.webp');
            $manager->persist($spectacle);
        }
        $manager->flush();
    }
}
