<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private $encoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);


        $mails = array("pierre.kelbert@gmail.com", "hugodiguardia@yahoo.fr", "clementd@outlook.com", "john.smith@uqac.ca", "joel.courtois@epita.fr");
        $firstnames = array("Pierre", "Hugo", "Clement", "John", "Joel");
        $lastnames = array("Kelbert", "DiGuardia", "Dupland", "Smith", "Courtois");
        $locations = array("6 rue du Rhin", "27 rue du lac saint Jean", "8 rue des chênes", "23 avenue des anglais", "7000 impasse de la banque");
        $passwords = array("pk_password", "hdg_password", "cd_password", "js_password", "jc_password");


        for ($i = 0; $i < 5; $i++)
        {
            $user = new User();

            $user->setPassword($this->encoder->encodePassword($user, $passwords[$i]));
            $user->setAddress($locations[$i]);
            $user->setEmail($mails[$i]);
            $user->setFirstname($firstnames[$i]);
            $user->setLastname($lastnames[$i]);

            $manager->persist($user);
        }



        $manager->flush();
    }
}
