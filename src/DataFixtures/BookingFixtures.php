<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 40; $i++) {
            $booking = new Booking();
            $booking->setUser($this->getReference('user_'.rand(0,9)));
            $booking->setNumberTicket(rand(1, 8));
            $booking->setTotalPrice($booking->getNumberTicket()*35);
            $booking->setSpectacle($this->getReference('spectacle_' . $i%10));
            $manager->persist($booking);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ SpectacleFixtures::class, UserFixtures::class];
    }
}
