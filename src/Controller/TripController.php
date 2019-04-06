<?php

namespace App\Controller;


use App\Entity\Reservation;
use App\Entity\Trip;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


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
        //TODO

        $this->tripRepository = $this->getDoctrine()->getRepository(Trip::class);

        $this->listTrip = $this->tripRepository->findLatest();
        //dump($this->listTrip);


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

        $this->tripRepository = $this->getDoctrine()->getRepository(Trip::class);

        $this->selectedTrip = $this->tripRepository->findOneBy(['id' => $id]);

        $this->reservationRepository = $this->getDoctrine()->getRepository(Reservation::class);

        $reservations = $this->reservationRepository->findReservation($id);    // Récupère la liste de passager associé au voyage
        $this->reserve = false; // Initialise la reservation du voyage à false
        foreach ($reservations as $row){   // Vérifie si l'utilisateur à déjà réservé le voyage
//            if ($row['user_id'] == $user['id']){
//                $this->reserve = true;    // Indique que l'utilisateur a déjà réservé le voyage
//            }
        }

        //Pierre
        $preferences = $this->selectedTrip
            ->getConductor()
            ->getUserPreferences()
        ;
        //Pierre end

        //dump($this->selectedTrip, $reservations, $this->reserve, $preferences);
        return $this->render('frontend/view.html.twig', [
            "trip" => $this->selectedTrip,
            "reservation" => $reservations[0],
            "hasReserved" => true,
            "userPreferences" => $preferences
        ]);
    }


    /**
     * @Route("/reservation/{id}", name="trip.reservation")
     *
     * Creates a new reservation for the user if he does not have one.
     * Otherwise, it is canceled.
     *
     */
    public function reservation()
    {
        //TODO
        //Il faut d'abord afficher un message de confirmation avant d'effectuer l'action evidemment.
    }


    /**
     * @Route("/add", name="trip.add")
     *
     * Edit the trip.
     *
     * @author cldupland
     */
    public function add()
    {
        //TODO
    }


    /**
     * @Route("/edit/{id}/admin", name="trip.edit")
     *
     * Edit the trip /!\ Only conductor can access this page !
     *
     * @author cldupland
     */
    public function edit() : Response
    {
        return new Response('salut');
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
        $this->tripRepository = $this->getDoctrine()->getRepository(Trip::class);

        $depart = "Paris";
        $arrive ="Lille";

        $display = $this->tripRepository->findTrip($depart, $arrive, null);
        dump($display);

        return new Response('');
    }
}
