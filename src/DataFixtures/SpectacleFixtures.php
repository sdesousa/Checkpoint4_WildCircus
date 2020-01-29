<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use App\Entity\Spectacle;
use \DateTime;

class SpectacleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $spectacle = new Spectacle();
            $spectacle->setName($faker->sentence(3));
            $spectacle->setPlace($faker->city);
            $spectacle->setCapacity(80);
            $spectacle->setDate($faker->dateTimeInInterval('now', '+3 month'));
            $spectacle->setUpdatedAt(new DateTime());
            $acts = array_rand(range(0,7), rand(2, 5));
            foreach ($acts as $act) {
                $spectacle->addAct($this->getReference('act_' . $act));
            }
            $manager->persist($spectacle);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ ActFixtures::class];
    }
}
