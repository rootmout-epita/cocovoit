<?php

namespace App\DataFixtures;

use App\Entity\Preference;
use App\Entity\User;
use App\Entity\UserPreference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserPreferenceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $pierre = array(
            1 => "Oui",
            2 => "Pop",
            3 => "Oui",
            5 => "Oui"
        );

        $hugo = array(
            1 => "Oui",
            2 => "HardRock",
            4 => "1 x 15kg"
        );

        $clement = array(
            2 => "Musique classique",
            3 => "Oui",
            4 => "2 x 10kg",
            5 => "Oui"
        );

        $john = array(
            4 => "3 x 5kg",
            5 => "Oui"
        );

        $joel = array(
            1 => "Trop",
            2 => "Musique zen",
            3 => "Oui",
        );

        $personnes = array(
            $pierre,
            $hugo,
            $clement,
            $john,
            $joel
        );


        for ($i = 0; $i < 5; $i++)
        {
            $user = $manager
                ->getRepository(User::class)
                ->find($i+1);

            $preferences = $manager
                ->getRepository(Preference::class)
                ->findAll();


            foreach ($personnes[$i] as $item => $value)
            {
                $elem = new UserPreference();

                /** @var User $user */
                $elem->setUser($user);

                $elem->setPreference($preferences[$item-1]);
                $elem->setValue($value);

                $manager->persist($elem);
            }
        }

        $manager->flush();
    }
}
