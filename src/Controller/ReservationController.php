<?php

namespace App\Controller;

use App\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/check/{reservation_key}", name="reservation.check")
     *
     * Check if the reservation key for a reservation already exists in the database or not.
     *
     * @param Request $request : the user's request
     *
     * @return Response
     *
     * @author hdiguardia
     */
    public function check(Request $request)
    {
        //TODO
        $reservation_key = $request->get('reservation_key');
        //$reservation_key = '9ac522286a381d4bdd0ded18875cf5bfcd4801ea';
        $key = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findOneBy(['reservation_key' => $reservation_key]);
        if (!$key) {
            $exist = false;
        }
        else {
            $exist = true;
        }

        dump($exist);
        return new Response('rien');
    }

    /**
     * @Route("/showticket/pk={pub_key}", name="reservation.showticket")
     */
    public function showTicket(string $pub_key)
    {
        return new Response($pub_key);
    }
}
