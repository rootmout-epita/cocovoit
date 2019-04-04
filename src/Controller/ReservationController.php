<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ReservationController
 * @package App\Controller
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("/check", name="reservation.check")
     */
    public function check()
    {
        //TODO
    }
}
