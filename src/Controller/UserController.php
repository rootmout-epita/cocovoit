<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/login" name="user.login")
     */
    public function login()
    {
        //TODO
    }


    /**
     * Logout the user, simple…
     *
     * @Route("/logout" name="user.logout")
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
     * Main page of the account section. Show all reservations made by user.
     *
     * @Route("/reservations", name="user.dashboard.reservations")
     */
    public function showReservations()
    {
        //TODO
    }


    /**
     * Second page of the dashboard. Show the trips created by user.
     *
     * @Route("/trips", name="user.dashboard.trips")
     */
    public function showTrips()
    {
        //TODO
    }
}
