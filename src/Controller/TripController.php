<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class TripController
 * @package App\Controller
 * @Route("/trip")
 */
class TripController extends AbstractController
{
    /**
     * @Route("/", name="trip.list")
     *
     * Display the lattest trips.
     *
     * @author cldupland
     */
    public function list()
    {
        //TODO
    }


    /**
     * @Route("/{id}", name="trip.view")
     *
     * Display the trip page, all informations about are displayed.
     * If the logged user has reserved this trip, this information is displayed too.
     *
     * @author cldupland
     */
    public function view()
    {
        //TODO donne acces à toutes les informations sur le trajet.

        /** Avant le trajet:
         * on affiche le bouton reserver s'il n'a pas reserver le trajet
         * "annuler" sinon
         *
         * Après le trajet:
         * l'utilisateur peut donner son avis.
         */

        // Boolean pour savoir si l'utilisateur a déjà réservé.
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
     * @Route("/edit/{id}", name="trip.edit")
     *
     * Edit the trip /!\ Only conductor can access this page !
     *
     * @author cldupland
     */
    public function edit()
    {
        //TODO
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
     * @Route("/search", name="trip.search")
     *
     * Search with the condition passed at GET request.
     *
     * @author cldupland
     */
    public function search()
    {
        //TODO
    }
}
