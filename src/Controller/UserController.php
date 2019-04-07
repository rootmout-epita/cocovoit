<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
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

        return $this->render('backend/security/register.html.twig', [
            'form' => $form->createView(),
        ]); // Template "registration" à créer!

    }


    /**
     * Enable user to change his personnal informations
     *
     * @Route("/edit", name="user.edit")
     *
     * @author hdiguardia
     */
    public function edit(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        //TODO : Ne s'occupe pas encore des préférences utilisateurs!
        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->flush();

            $this->addFlash('success', 'Votre compte à bien été modifié.');
            return $this->redirectToRoute('user.edit');
        }

        return $this->render('security/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /** NOT USED FOR MOMENT
     * Enable user to delete his account
     *
     * @Route("/edit/admin", name="user.delete")
     *
     * @author hdiguardia
     */
//    public function delete()
//    {
//        $user = $this->getUser();
//        $role = $user->getRoles();
//        $admin = false;
//        foreach ($role as $role) {
//            if ($role = 'ROLE_ADMIN') {
//                $admin = true;
//            }
//        }
//        if($admin)
//        {
//            $this->em->remove($user);
//            $this->em->flush();
//            $this->addFlash('success', 'Le compte a été supprimé avec succès.');
//        }
//        else
//        {
//            $this->addFlash('error', 'Vous ne pouvez pas supprimer ce compte.');
//        }
//        return $this->redirectToRoute('trip.view');
//    }


    /**
     * Main page of the account section. Show all reservations and trips.
     *
     * @Route("/dashboard", name="user.dashboard")
     *
     * @author cldupland
     */
    public function dashboard()
    {
        //TODO retourne à la vue une liste avec toutes les reservations.

        return $this->render('backend/dashboard.html.twig', [
            "reservations" => $this
                ->getUser()
                ->getReservations(),
            "trips" => $this
                ->getUser()
                ->getTrips(),
            "user" => $this->getUser()
        ]);
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

        //dump($this->getUser()->getId());

        return $this->render('frontend/user_page.html.twig',[
            "user" => $user,
            "userPreferences" => $user->getUserPreferences(),
            "isMe" => null !== $this->getUser() ? $this->getUser()->getId() == $user->getId() : false
        ]);
    }
}
