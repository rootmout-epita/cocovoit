<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UserController extends AbstractController
{
    private $userRepository;

    /**
     * Show the login form for connection.
     *
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('backend/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }


    /**
     * Registration form.
     *
     * @Route("/register", name="user.registration")
     */
    public function register()
    {
        //TODO
    }


    /**
     * Enable user to change his personnal informations
     *
     * @Route("/edit", name="user.edit")
     */
    public function edit()
    {
        //TODO
    }


    /** NOT USED FOR MOMENT
     * Enable user to delete his account
     *
     * @Route("/edit", name="user.edit")
     */
    //public function edit()
    //{
    //}


    /**
     * Main page of the account section. Show all reservations made by user.
     *
     * @Route("/reservations", name="user.dashboard.reservations")
     */
    public function showReservations()
    {
        //TODO retourne à la vue une liste avec toutes les reservations.
    }


    /**
     * Second page of the dashboard. Show the trips created by user.
     *
     * @Route("/trips", name="user.dashboard.trips")
     */
    public function showTrips()
    {
        //TODO idem mais avec les trajets crées
    }


    /**
     * @param int id : selected user id
     *
     * Display the public page of the user. We can see his informations and we can see the
     * feedback of his trips. You dont have to be logged-in.
     *
     * @Route("/user_page/{id}", name="user.public_page")
     *
     * @return Reponse
     */
    public function publicPage(int $id) : Response
    {
        if ($this->userRepository == null){
            $this->userRepository = $this->getDoctrine()->getRepository(User::class);
        }

        $user = $this->userRepository->findOneBy(['id' => $id]);

        dump($user);

        return new Response('');
    }
}
