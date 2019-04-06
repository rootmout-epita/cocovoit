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
<<<<<<< HEAD
     * @Route("/new", name="reservation.new")
     *
     * Crée une nouvelle réservation si l'utilisateur est connecté.
     *
     * @param Request $request : La requête à traiter lorsque l'utilisateur a appuyé sur le bouton "Réserver"
     *
     * @return Response
     *
     * @author hdiguardia
     */
    public function new(Request $request)
    {
        //TODO
        // Crée un formulaire qui va gérer la requête envoyée par l'utilisateur
        $reservation = new Reservation();
        /*$form = $this->createForm(ReservationFormType::class, $reservation); // CREER le formulaire de réservation
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // Vérifie si l'utilisateur est bien connecté
            $isFullyAuthenticated = $this->get('security.context')
                ->isGranted('IS_AUTHENTICATED_FULLY');
            if(!$isFullyAuthenticated)
            {
                $this->addFlash('error', 'Veuillez vous connecter afin de réserver un voyage.');
                return $this->redirectToRoute('trip.view');
            }

            // S'il n'y a aucun problème, crée une nouvelle réservation
            $reservation->setTrip(/trip/reserver/{id}); // Pas sûr de savoir comment avoir le Trip à réserver
            $reservation->setClient($this->getUser()->getId());
            $this->em->persist($account);
            $this->em->flush();
            $this->addFlash('success', 'Le voyage a bien été réservé.');
            return $this->redirectToRoute('user.dashboard.reservations');
        }

        return $this->render('accounts/new.html.twig', [
            'account' => $account,
            'form' => $form->createView(),
        ]);*/


    }


    /**
     * @Route("/delete", name="reservation.delete")
     */
    public function delete()
    {
        //TODO
    }


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
}
