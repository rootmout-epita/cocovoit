<?php

namespace App\Controller;

use App\Entity\EmailChecker;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Services\MailConfirmation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $userRepository;

    /**
     * Show the login form for connection.
     *
     * @Route("/login", name="login")
     *
     * @param AuthenticationUtils $authenticationUtils : Used to get the last username
     *
     * @return Response
     *
     * @author cldupland
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $verification = $this
            ->getDoctrine()
            ->getRepository(EmailChecker::class)
            ->findOneBy(["mail" => $lastUsername]);


        $hey = null;
        if(isset($verification))
        {
            $hey = "L'adresse e-mail n'est pas vérifiée";
            $error = null;
        }

        //TODO proposer de renvoyer

        return $this->render('backend/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'hey' => $hey
        ]);
    }


    /**
     * Registration form.
     *
     * @Route("/register", name="user.registration")
     *
     * @param Request $request : the request sent by the user
     *
     * @param EntityManagerInterface $manager : used to register the new user in the database
     *
     * @param UserPasswordEncoderInterface $encoder : used to hash the password the user entered
     *
     * @return Response
     *
     * @author hdiguardia
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, MailConfirmation $confirmation) {

        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $emailVerification = new EmailChecker();
            $emailVerification->setUser($user);
            $emailVerification->setMail($user->getEmail());
            $emailVerification->setCheckKey(crypt(random_bytes(5), "md5"));

            $user->setEmail($emailVerification->getCheckKey()."@a.com");

            $manager->persist($user);
            $manager->persist($emailVerification);
            $manager->flush();

            $sender_address = $this->getParameter('app.sender_address');
            $confirmation->sendMail($emailVerification, $sender_address);

            $this->addFlash('success', 'Votre compte à bien été crée, veuillez vérifier votre boite e-mail pour confirmer.');
            return $this->redirectToRoute('login');
        }

        return $this->render('backend/security/register.html.twig', [
            'form' => $form->createView(),
        ]); // Template "registration" à créer!

    }


    /**
     * Enable user to change his personnal informations.
     *
     * @Route("/edit", name="user.edit")
     *
     * @param Request $request : the request sent by the user
     *
     * @param EntityManagerInterface $manager : used to register the new user in the database
     *
     * @param UserPasswordEncoderInterface $encoder : used to hash the password the user entered
     *
     * @return Response
     *
     * @author hdiguardia
     */
    public function edit(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        // Get the user connected right now
        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->flush();

            $this->addFlash('success', 'Votre compte à bien été modifié.');
            return $this->redirectToRoute('user.dashboard');
        }

        return $this->render('backend/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /** NOT USED
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
     * @return Response
     *
     * @author cldupland
     */
    public function dashboard()
    {
        // Return to the view a list with all the reservations

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
     * @return Response
     *
     * @author cldupland
     */
    public function publicPage(int $id) : Response
    {
        if ($this->userRepository == null){
            $this->userRepository = $this->getDoctrine()->getRepository(User::class);
        }

        $user = $this->userRepository->findOneBy(['id' => $id]);

        return $this->render('frontend/user_page.html.twig',[
            "user" => $user,
            "userPreferences" => $user->getUserPreferences(),
            "isMe" => null !== $this->getUser() ? $this->getUser()->getId() == $user->getId() : false
        ]);
    }
}
