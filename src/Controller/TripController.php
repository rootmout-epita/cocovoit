<?php

namespace App\Controller;


use App\Entity\Reservation;
use App\Entity\Trip;
use App\Form\TripType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


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
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
     * @param int $id : id of the trip that has been selected
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
        /** Before the trip :
         * display the "book" button if the user hasn't booked the trip yet
         *
         * After the trip :
         * the user can give his feedback
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

            $reservation = $this->reservationRepository->findReservation($id, $user->getId());    // R??cup??re la liste de passager associ?? au voyage

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
     * @param Request $request : the request sent by the user
     *
     * @return Response
     *
     * @author hdiguardia
     * @throws \Dompdf\Exception
     */
    public function reservation(Request $request)
    {
        // Check if the user is connected
        $user = $this->getUser();
        if($user == null)
        {
            $this->addFlash('error', 'Veuillez vous connecter afin de r??server un voyage.');
            return $this->redirectToRoute('user.dashboard');
        }
        else {
            // Get the values used to check the data in the database
            $trip_id = $request->get('id');
            $trip = $this->getDoctrine()
                ->getRepository(Trip::class)
                ->findOneBy(['id' => $trip_id]);

            $reservation = $this->getDoctrine()
                ->getRepository(Reservation::class)
                ->findOneBy(['trip' => $trip, 'user' => $user]);

            // Check if the user is trying to book his own trip
            if ($trip->getConductor() == $user) {
                $this->addFlash('error', 'Vous ne pouvez pas r??server votre propre trajet.');
                return $this->redirectToRoute('user.dashboard');
            }
            else {

                // Check if the user is trying to book a trip already full
                if ($trip->remainingPlaces() == 0) {
                    $this->addFlash('error', 'Vous ne pouvez pas r??server un trajet d??j?? complet.');
                    return $this->redirectToRoute('user.dashboard');
                }
                else {

                    // If the reservation doesn't exist, create it
                    if (!$reservation) {
                        $reservation = new Reservation();
                        $reservation->setTrip($trip);
                        $reservation->setUser($user);

                        $this->em->persist($reservation);
                        $this->em->flush();

                        $this->addFlash('success', 'Le voyage a bien ??t?? r??serv??.');

                        // THIS CODE SECTION DOESN'T WORK
//                        //PDF SECTION
//                        // Configure Dompdf according to your needs
//                        $pdfOptions = new Options();
//                        $pdfOptions->set('isRemoteEnabled',true);
//
//                        // Instantiate Dompdf with our options
//                        $dompdf = new Dompdf($pdfOptions);
//
//
//                        // Retrieve the HTML generated in our twig file
//                        $html = $this->renderView('ticket.pdf.twig', [
//                            'title' => "Welcome to our PDF Test"
//                        ]);
//
//                        // Load HTML to Dompdf
//                        $dompdf->loadHtml($html);
//                        dump($html);
//
//                        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
//                        //$dompdf->setPaper('A4', 'portrait');
//
//                        // Render the HTML as PDF
//                        $dompdf->render();
//
//                        // Store PDF Binary Data
//                        $output = $dompdf->output();
//                        dump($output);
//
//                        // In this case, we want to write the file in the public directory
//                        //$publicDirectory = $this->get('kernel')->getProjectDir() . '/public';
//                        // e.g /var/www/project/public/mypdf.pdf
//                        $pdfFilepath =  'mypdf.pdf';
//
//                        // Write file to the desired path
//                        file_put_contents($pdfFilepath, $output);
//                        //END PDF
                    }

                    // Otherwise, delete it
                    else {
                        $this->em->remove($reservation);
                        $this->em->flush();
                        $this->addFlash('success', 'La r??servation a bien ??t?? annul??e.');
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
     * @param Request $request : the request sent by the user
     *
     * @return  Response
     *
     * @author cldupland
     */
    public function add(Request $request)
    {
        $trip = new Trip();
        $form = $this->createForm(TripType::class, $trip);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($trip->getDepartureSchedule() < new \DateTime())
            {
                $this->addFlash('error', 'Attention Marty, tu tente de retourner dans le pass?? !');
                return $this->redirectToRoute("user.dashboard");
            }
            $trip->setConductor($this->getUser());
            $this->em->persist($trip);
            $this->em->flush();
            $this->addFlash('success', 'Votre trajet ?? bien ??t?? cr??e.');
            return $this->redirectToRoute("user.dashboard");
        }

        return $this->render('backend/trip/add.html.twig', [
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/edit/{id}/admin", name="trip.edit")
     *
     * Edit the trip /!\ Only conductor can access this page !
     *
     * @param int $id : the id of the trip
     *
     * @param Request $request : the request sent by the user
     *
     * @return Response
     *
     * @author cldupland
     */
    public function edit(int $id, Request $request) : Response
    {
        if ($this->tripRepository == null){
            $this->tripRepository = $this->getDoctrine()->getRepository(Trip::class);
        }

        $selectedTrip = $this->tripRepository->findOneBy(['id' => $id]);


        $form = $this->createForm(TripType::class, $selectedTrip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if($selectedTrip->getDepartureSchedule() < new \DateTime())
            {
                $this->addFlash('error', 'Attention Marty, tu tente de retourner dans le pass?? !');
                return $this->redirectToRoute("user.dashboard");
            }
            $this->em->flush();
            $this->addFlash('success', 'Votre trajet ?? bien ??t?? modifi??.');
            return $this->redirectToRoute("user.dashboard");
        }

        return $this->render('backend/trip/edit.html.twig', [
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/delete/{id}", name="trip.delete")
     *
     * Delete the trip. /!\ Only conductor can access this page !
     *
     * @param int $id : the id of the trip to delete
     *
     * @return Response
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
     * @Route("/cancel/{id}", name="trip.cancel")
     *
     * Cancel a trip.
     *
     * @param int $id : the id of the trip to delete
     *
     * @return Response
     *
     * @author pkelbert
     */
    public function cancel(int $id)
    {
        $this
            ->getDoctrine()
            ->getRepository(Trip::class)
            ->findOneBy(["id" => $id, "conductor" => $this->getUser()->getId()])
            ->setCanceled(true);

        $this->getUser()->newCanceledTrip();

        $this->em->flush();
        $this->addFlash('success', 'Votre trajet ?? bien ??t?? annul??.');

        return $this->redirectToRoute("user.dashboard");

    }


    /**
     * @Route("/search", name="trip.search")
     *
     * @param Request $request : the request sent by the user
     *
     * Search with the condition passed at GET request.
     *
     * @return Response
     *
     * @author cldupland & pkelbert
     */
    public function search(Request $request) : Response //$depart, $arrive, $dateDepart
    {
        $display = null;
        $submited = false;
        $trip = new Trip();
        $trip->setNbrPlaces(1);
        $trip->setDuration(date_create("1:00"));
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $submited = true;

            $depart = $trip->getDeparturePlace();
            $arrive = $trip->getArrivalPlace();
            $prix = $trip->getPrice();

            //clement TODO
            $display = $this->getDoctrine()->getRepository(Trip::class)->findTrip($depart, $arrive, null);
        }


        return $this->render('frontend/search.html.twig', [
            "form" => $form->createView(),
            "trips" => $display,
            "hidde_trip_list" => !$submited
        ]);
    }
}
