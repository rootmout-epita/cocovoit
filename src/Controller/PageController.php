<?php

namespace App\Controller;

use App\Entity\EmailChecker;
use App\Entity\Trip;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * Display the home page and give access to the rest of site
     *
     * @Route("/", name="page.home")
     */
    public function index()
    {
        $users= $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->nbr();

        $trips = $this
            ->getDoctrine()
            ->getRepository(Trip::class)
            ->nbr();

        return $this->render('page/home.html.twig',[
            "nbr_user" => $users,
            "nbr_trip" => $trips
        ]);
    }


    /**
     * Display the about page.
     *
     * @Route("/about", name="page.about")
     */
    public function about()
    {
        return $this->render('page/about.html.twig');
    }

    /**
     * @Route("/test")
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function mailtest(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('noreply-cocovoit@lab.kelbert.fr')
            ->setTo('pierre.kelbert@gmail.com')
            ->setBody(
                "YESS",
                'text/html'
            );

        $mailer->send($message);

        return new Response("hello");
    }

    /**
     * @Route("/rendermail")
     * @return Response
     */
    public function mailRenderTest(){

        $checker = new EmailChecker();
        $checker->setCheckKey("abc");
        $checker->setMail("pierre.kelbert@gmail.com");
        $user = new User();
        $user->setFirstname("Pierre");
        $user->setLastname("TEST");
        $checker->setUser($user);

        return $this->render('mail/confirmMail.html.twig', [
            "checker" => $checker
        ]);
    }

}
