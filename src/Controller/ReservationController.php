<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReservationController
 * @package App\Controller
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("/new", name="reservation.new")
     */
    public function new()
    {
        //TODO
    }


    /**
     * @Route("/delete", name="reservation.delete")
     */
    public function delete()
    {
        //TODO
    }


    /**
     * @Route("/check", name="reservation.check")
     */
    public function check()
    {
        //TODO
    }
}
