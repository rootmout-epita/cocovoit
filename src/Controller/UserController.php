<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $userRepository;

    /**
     * Show the login form for connection.
     *
     * @Route("/login", name="login")
     *
     * @author cldupland
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('backend/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }


    /**
     * Registration form.
     *
     * @Route("/register", name="user.registration")
     *
     * @author hdiguardia
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {

        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre compte à bien été crée.');
            return $this->redirectToRoute('login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);

    }


    /**
     * Enable user to change his personnal informations
     *
     * @Route("/edit", name="user.edit")
     *
     * @author hdiguardia
     */
    public function edit()
    {
        //TODO
    }


    /** NOT USED FOR MOMENT
     * Enable user to delete his account
     *
     * @Route("/edit/admin", name="user.edit")
     *
     * @author hdiguardia
     */
    public function editAdmin()
    {

    }


    /**
     * Main page of the account section. Show all reservations made by user.
     *
     * @Route("/reservations", name="user.dashboard.reservations")
     *
     * @author cldupland
     */
    public function showReservations()
    {
        //TODO retourne à la vue une liste avec toutes les reservations.
    }


    /**
     * Second page of the dashboard. Show the trips created by user.
     *
     * @Route("/trips", name="user.dashboard.trips")
     *
     * @author cldupland
     */
    public function showTrips()
    {
        //TODO idem mais avec les trajets crées
    }


    /**
     * @param int id : selected user id
     *
     * Display the public page of the user. We can see his informations and we can see the
     * feedback of his trips. You dont have to be logged-in.
     *
     * @Route("/user_page/{id}", name="user.public_page")
     *
     * @return Reponse
     *
     * @author cldupland
     */
    public function publicPage(int $id) : Response
    {
        if ($this->userRepository == null){
            $this->userRepository = $this->getDoctrine()->getRepository(User::class);
        }

        $user = $this->userRepository->findOneBy(['id' => $id]);

//        dump($user);

        return new Response('');
    }
}
