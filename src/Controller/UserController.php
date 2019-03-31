<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/account")
 */
class UserController extends AbstractController
{
    /**
     * Show the login form for connection.
     *
     * @Route("/login", name="user.login")
     */
    public function login()
    {
        //TODO
    }


    /**
     * Logout the user, simple…
     *
     * @Route("/logout", name="user.logout")
     */
    public function logout()
    {
        //TODO

        //attention, a part une simple redirection rien n'est demandé de plus. Symfony gère lui même la déconnexion.
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
     * Display the public page of the user. We can see his informations and we can see the
     * feedback of his trips. You dont have to be logged-in.
     *
     * @Route("/page", name="user.public_page")
     */
    public function publicPage()
    {
        //TODO
    }
}
