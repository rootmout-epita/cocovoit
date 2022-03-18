<?php

namespace App\DataFixtures;

use App\Entity\Trip;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TripFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);


        $conductor_id = array(1, 4, 3, 1, 1, 3);
        $citys = array("Lille", "Paris", "Marseille", "Lyon", "Toulouse", "Strasbourg", "Nice", "Rennes", "Lyon", "Magstatt-le-bas");
        $shedules = array("2019-03-19 09:00:00", "2019-03-23 13:30:00", "2019-03-23 17:20:00", "2019-03-29 08:00:00", "2019-03-30 09:00:00", "2019-03-30 09:45:00");
        $durations = array("02:00", "04:10", "02:30", "01:07", "5:40", "01:50");
        $places = array(3,4,2,3,5,1);
        $prices = array(12,20,10,9,24,11);


        //Trip generation
        for($i = 0; $i < 6; $i++)
        {
            $trip = new Trip();
            $trip->setDeparturePlace($citys[array_rand($citys)]);
            $trip->setArrivalPlace($citys[array_rand($citys)]);
            $trip->setDepartureSchedule(\date_create($shedules[$i]));
            $trip->setDuration(\date_create($durations[$i]));
            $trip->setNbrPlaces($places[$i]);
            $trip->setPrice($prices[$i]);


            //Load the user object.
            $user = $manager
                ->getRepository(User::class)
                ->find($conductor_id[$i]);


            /** @var User $user */
            $trip->setConductor($user);

            $manager->persist($trip);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
