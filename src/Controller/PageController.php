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
    public function mailtest(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('test@lab.kelbert.fr')
            ->setTo('pierre.kelbert@gmail.com')
            ->setBody(
                "salut",
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
            */
        ;

        $mailer->send($message);

        return new Response("test");
    }

}
