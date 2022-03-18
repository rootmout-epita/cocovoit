<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Entity\Trip;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $custormers = array(2,3,5);
        $trips = array(5,4,6);

        for($i = 0; $i < 3; $i++) {
            $reservation = new Reservation();

            $custormer = $manager
                ->getRepository(User::class)
                ->find($custormers[$i]);


            $trip = $manager
                ->getRepository(Trip::class)
                ->find($trips[$i]);


            /** @var User $custormer */
            $reservation->setUser($custormer);
            /** @var Trip $trip */
            $reservation->setTrip($trip);

            $manager->persist($reservation);
        }

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
