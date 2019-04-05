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
     * @Route("/check", name="reservation.check")
     */
    public function check()
    {
        //TODO
    }
}
