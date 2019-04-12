<?php

namespace App\Controller;

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
        //TODO

        return $this->render('page/home.html.twig');
        return new Response("<body>Ceci est la page d'accueil</body>");
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
    public function mailtest()
    {
        return $this->render('mail/confirmMail.html.twig');
    }

}
