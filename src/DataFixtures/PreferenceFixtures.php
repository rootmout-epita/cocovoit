<?php

namespace App\DataFixtures;

use App\Entity\Preference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PreferenceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $name = ["Aime bien parler", "Musique", "Accepte les enfants", "Grand volume valises", "Voiture electrique"];

        for($i = 0; $i < 5; $i++)
        {
            $preference = new Preference();
            $preference->setName($name[$i]);
            $manager->persist($preference);
        }

        $manager->flush();
    }
}
