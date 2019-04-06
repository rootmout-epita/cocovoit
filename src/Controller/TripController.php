<?php

namespace App\Controller;


use App\Entity\Reservation;
use App\Entity\Trip;
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
    private $listTrip;
    private $reserve;
    private $selectedTrip;
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

        $this->listTrip = $this->tripRepository->findLatest();


        return $this->render('frontend/list.html.twig', [
            "trips" => $this->listTrip
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

        $this->selectedTrip = $this->tripRepository->findOneBy(['id' => $id]);

        $this->reservationRepository = $this->getDoctrine()->getRepository(Reservation::class);

        $reservations = $this->reservationRepository->findReservation($id);    // Récupère la liste de passager associé au voyage
        $this->reserve = false; // Initialise la reservation du voyage à false
        $user = $this->getUser();

        if ($user != null){
            foreach ($reservations as $reservation){   // Vérifie si l'utilisateur à déjà réservé le voyage
                $reservedUser = $reservation->getUser();
                if ($reservedUser->getId() == $user->getId()){
                    $this->reserve = true;    // Indique que l'utilisateur a déjà réservé le voyage
                    break;
                }
            }
        }

        //Pierre
        $preferences = $this->selectedTrip
            ->getConductor()
            ->getUserPreferences()
        ;
        //Pierre end

        if ($reservation == null){
            return $this->render('frontend/view.html.twig', [
                "trip" => $this->selectedTrip,
                "hasReserved" => $this->reserve,
                "userPreferences" => $preferences
            ]);
        }
        return $this->render('frontend/view.html.twig', [
            "trip" => $this->selectedTrip,
            "reservation" => $reservation,
            "hasReserved" => $this->reserve,
            "userPreferences" => $preferences
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
            return $this->redirectToRoute('trip.view');
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
            return $this->redirectToRoute('user.dashboard.reservations');
        }

    }


    /**
     * @Route("/add", name="trip.add")
     *
     * Edit the trip.
     *
     * @author cldupland
     */
    public function add() : Response
    {
        if ($this->tripRepository == null){
            $this->tripRepository = $this->getDoctrine()->getRepository(Trip::class);
        }

        $dCity = "Clermont-Ferrand";
        $aCity = "Montpellier";
        $shedule = "01-01-2019 19:00:00";
        $duration = "31-12-2018 19:00:00";
        $place = 0;
        $price = 0;

        $user = $this->getUser();

        $trip = new Trip();
        $trip->setDeparturePlace($dCity)
            ->setArrivalPlace($aCity)
            ->setDepartureSchedule(\date_create($shedule))
            ->setDuration(\date_create($duration))
            ->setNbrPlaces($place)
            ->setPrice($price)
            ->setConductor($user);
        $m = $this->getDoctrine()->getManager();
        $m->persist($trip);
        $m->flush();

        return new Response('Tous c\'est bien passé');
    }


    /**
     * @Route("/edit/{id}/admin", name="trip.edit")
     *
     * Edit the trip /!\ Only conductor can access this page !
     *
     * @author cldupland
     */
    public function edit(int $id) : Response
    {
        if ($this->tripRepository == null){
            $this->tripRepository = $this->getDoctrine()->getRepository(Trip::class);
        }

        $this->selectedTrip = $this->tripRepository->findOneBy(['id' => $id]);



        return new Response('');
    }


    /**
     * @Route("/delete/{id}", name="trip.delete")
     *
     * Delete the trip. /!\ Only conductor can access this page !
     *
     * @author cldupland
     */
    public function delete()
    {
        //TODO
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
