<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Price;

class PriceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $price = new Price();
        $price->setKid(15);
        $price->setAdult(35);
        $price->setSenior(25);
        $manager->persist($price);
        $manager->flush();
    }
}
