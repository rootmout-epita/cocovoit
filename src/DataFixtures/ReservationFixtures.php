<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Entity\Trip;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $reservation = new Reservation();

        $custormer = $manager
            ->getRepository(User::class)
            ->findOneBy(['firstname' => "Pierre"]);


        $trip = $manager
            ->getRepository(Trip::class)
            ->findOneBy(['price' => 9]);


        /** @var User $custormer */
        $reservation->setUser($custormer);

        /** @var Trip $trip */
        $reservation->setTrip($trip);

        $manager->persist($reservation);

        $manager->flush();
    }


    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            TripFixtures::class
        );
    }
}
