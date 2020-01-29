<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use App\Entity\Act;
use \DateTime;

class ActFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i<8 ; $i++) {
            $act = new Act();
            $act->setName($faker->word());
            $act->setDescription($faker->paragraph(4));
            $act->setCreated($faker->dateTimeInInterval('-5 years', 'now'));
            $act->setPicture('circus-act.webp');
            $manager->persist($act);
        }
        $manager->flush();
    }
}
