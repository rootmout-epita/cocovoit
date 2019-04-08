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
        //Get the reservation key from the url
        $reservation_key = $request->get('reservation_key');

        $reservation = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findOneBy(['reservation_key' => $reservation_key]);

        return $this->render('check_reservation.html.twig', [
            "reservation" => $reservation
        ]);
    }

    /**
     * @Route("/showticket/pk={pub_key}", name="reservation.showticket")
     *
     * Display the ticket's QR code.
     *
     * @param String $pub_key : the ticket's path
     *
     * @return Response
     *
     * @author pkelbert
     */
    public function showTicket(string $pub_key)
    {
        $reservation = $this
            ->getDoctrine()
            ->getRepository(Reservation::class)
            ->findOneBy(["ticket_path" => $pub_key]);

        $default_path = "http://lab.kelbert.fr/reservation/check/";

        return $this->render('ticket.pdf.twig',[
            "key" => $default_path . $reservation->getReservationKey()
        ]);
    }
}
