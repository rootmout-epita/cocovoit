<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\EmailChecker;
use Doctrine\ORM\EntityManagerInterface;

class EmailController extends AbstractController
{
    /**
     * @Route("/checkmail/{mailkey}", name="mail_check")
     * @param string $mailkey
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkEmail(string $mailkey, EntityManagerInterface $manager)
    {
        /** @var \App\Entity\EmailChecker $checker */
        $checker = $this
            ->getDoctrine()
            ->getRepository(EmailChecker::class)
            ->findOneBy(["check_key" => $mailkey]);

        $confirmed = false;
        $mail = null;

        if(isset($checker))
        {
            /** @var \App\Entity\User $user */
            $user = $checker->getUser();
            $user->setEmail($checker->getMail());
            $manager->remove($checker);
            $manager->flush();

            $mail = $user->getEmail();
            $confirmed = true;
        }

        return $this->render('backend/security/confirm_mail.html.twig',[
            "confirmed" => $confirmed,
            "email" => $mail
        ]);
    }
}