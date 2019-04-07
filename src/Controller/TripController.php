<?php

namespace App\Controller;


use App\Entity\Reservation;
use App\Entity\Trip;
use App\Form\TripType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * Class TripController
 * @package App\Controller
 * @Route("/trip")
 */
class TripController extends AbstractController
{
    private $tripRepository;
    private $reservationRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

//    public function __construct(PropertyRepository $repository)
//    {
//        $this->tripRepository = $repository;
//    }

    /**
     * @Route("/", name="trip.list")
     *
     * Display the lattest trips.
     *
     * @return Response
     *
     * @author cldupland
     */
    public function list() : Response
    {
        if ($this->tripRepository == null){
            $this->tripRepository = $this->getDoctrine()->getRepository(Trip::class);
        }

        $listTrip = $this->tripRepository->findLatest();


        return $this->render('frontend/list.html.twig', [
            "trips" => $listTrip
        ]);
    }


    /**
     * @Route("/view/{id}", name="trip.view")
     *
     * @param int $id : id du voyage séléctionné au préalable
     *
     * Display the trip page, all informations about are displayed.
     * If the logged user has reserved this trip, this information is displayed too.
     *
     * @return Response
     *
     * @author cldupland
     */
    public function view(int $id) : Response
    {
        //TODO donne acces à toutes les informations sur le trajet.

        /** Avant le trajet:
         * on affiche le bouton reserver s'il n'a pas reserver le trajet
         * "annuler" sinon
         *
         * Après le trajet:
         * l'utilisateur peut donner son avis.
         */

        if ($this->tripRepository == null){
            $this->tripRepository = $this->getDoctrine()->getRepository(Trip::class);
        }

        $selectedTrip = $this->tripRepository->findOneBy(['id' => $id]);

        $this->reservationRepository = $this->getDoctrine()->getRepository(Reservation::class);

        $user = $this->getUser();

        $preferences = $selectedTrip
            ->getConductor()
            ->getUserPreferences();

        if ($user != null){

            $reservation = $this->reservationRepository->findReservation($id, $user->getId());    // Récupère la liste de passager associé au voyage

            $isConductor = false;

            if ($selectedTrip->getConductor() == $user){
                $isConductor = true;
            }

            if ($reservation == []){
                $reserve = false;
                return $this->render('frontend/view.html.twig', [
                    "trip" => $selectedTrip,
                    "hasReserved" => $reserve,
                    "userPreferences" => $preferences,
                    "isConductor" => $isConductor
                ]);
            }
            else{
                $reserve = true;
                return $this->render('frontend/view.html.twig', [
                    "trip" => $selectedTrip,
                    "reservation" => $reservation[0],
                    "hasReserved" => $reserve,
                    "userPreferences" => $preferences,
                    "isConductor" => $isConductor
                ]);
            }
        }

        $reserve = false;

        return $this->render('frontend/view.html.twig', [
            "trip" => $selectedTrip,
            "hasReserved" => $reserve,
            "userPreferences" => $preferences,
            "isConductor" => false
        ]);
    }


    /**
     * @Route("/reservation/{id}", name="trip.reservation")
     *
     * Creates a new reservation for the user if he does not have one.
     * Otherwise, it is canceled.
     *
     * @author hdiguardia
     */
    public function reservation(Request $request)
    {
        //TODO
        //Il faut d'abord afficher un message de confirmation avant d'effectuer l'action evidemment.
        // Regarde si l'utilisateur est connecté
        $user = $this->getUser();
        if($user == null)
        {
            $this->addFlash('error', 'Veuillez vous connecter afin de réserver un voyage.');
            return $this->redirectToRoute('user.dashboard');
        }
        else {
            // Prend les valeurs utilisées pour vérifier les données
            $trip_id = $request->get('id');
            $trip = $this->getDoctrine()
                ->getRepository(Trip::class)
                ->findOneBy(['id' => $trip_id]);

            $reservation = $this->getDoctrine()
                ->getRepository(Reservation::class)
                ->findOneBy(['trip' => $trip, 'user' => $user]);

            // Vérifie si l'utilisateur essaye de réserver son propre trajet
            if ($trip->getConductor() == $user) {
                $this->addFlash('error', 'Vous ne pouvez pas réserver votre propre trajet.');
                return $this->redirectToRoute('user.dashboard');
            }
            else {

                // Vérifie si l'utilisateur essaye de réserver un trajet complet
                if ($trip->remainingPlaces() == 0) {
                    $this->addFlash('error', 'Vous ne pouvez pas réserver un trajet déjà complet.');
                    return $this->redirectToRoute('user.dashboard');
                }
                else {

                    // Si la réservation n'existe pas, la créer
                    if (!$reservation) {
                        $reservation = new Reservation();
                        $reservation->setTrip($trip);
                        $reservation->setUser($user);

                        $this->em->persist($reservation);
                        $this->em->flush();

                        $this->addFlash('success', 'Le voyage a bien été réservé.');
                    }

                    // Sinon, la supprimer
                    else {
                        $this->em->remove($reservation);
                        $this->em->flush();
                        $this->addFlash('success', 'La réservation a bien été annulée.');
                    }
                    return $this->redirectToRoute('user.dashboard');
                }
            }
        }

    }

    /**
     * @Route("/add", name="trip.add")
     *
     * Add the trip.
     *
     * @author cldupland
     */
    public function add(Request $request)
    {
        $trip = new Trip();
        $form = $this->createForm(TripType::class, $trip);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($trip);
            $this->em->flush();
            return $this->redirectToRoute("trip.list");
        }

        return $this->render('backend/trip/edit.html.twig');
    }


    /**
     * @Route("/edit/{id}/admin", name="trip.edit")
     *
     * Edit the trip /!\ Only conductor can access this page !
     *
     * @author cldupland
     */
    public function edit(int $id, Request $request) : Response
    {
        if ($this->tripRepository == null){
            $this->tripRepository = $this->getDoctrine()->getRepository(Trip::class);
        }

        $selectedTrip = $this->tripRepository->findOneBy(['id' => $id]);

        dump($selectedTrip);

        $form = $this->createForm(TripType::class, $selectedTrip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            return $this->redirectToRoute("trip.list");
        }

        return $this->render('backend/trip/edit.html.twig');
    }


    /**
     * @Route("/delete/{id}", name="trip.delete")
     *
     * Delete the trip. /!\ Only conductor can access this page !
     *
     * @author cldupland
     */
    public function delete(int $id)
    {
        if ($this->tripRepository == null){
            $this->tripRepository = $this->getDoctrine()->getRepository(Trip::class);
        }

        $selectedTrip = $this->tripRepository->findOneBy(['id' => $id]);

        dump($selectedTrip);

        $this->em->remove($selectedTrip);
        $this->em->flush();

        return $this->redirectToRoute("trip.list");
    }


    /**
     * @Route("/search")
     *
     * @param string $depart : Lieu de départ du voyage
     * @param string $arrive : Lieu d'arriver du voyage
     * @param date $dateDepart : Date du départ du voyage
     *
     * Search with the condition passed at GET request.
     *
     * @return Response
     *
     * @author cldupland
     */
    public function search() : Response //$depart, $arrive, $dateDepart
    {
        if ($this->tripRepository == null){
            $this->tripRepository = $this->getDoctrine()->getRepository(Trip::class);
        }
        $depart = "Paris";
        $arrive ="Lille";

        $display = $this->tripRepository->findTrip($depart, $arrive, null);
        dump($display);

        return new Response('');
    }
}
