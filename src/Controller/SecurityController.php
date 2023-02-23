<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Security\AppUserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(MailerInterface $mailer,
                          AuthenticationUtils $authenticationUtils,
                          Request $request,
                          UserPasswordHasherInterface $userPasswordHasher,
                          UserAuthenticatorInterface $userAuthenticator,
                          AppUserAuthenticator $authenticator,
                          EntityManagerInterface $entityManager): Response
    {
        $formUser = $this->createForm(UserType::class);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {

            return $this->redirectToRoute('app_burger_index', [], Response::HTTP_SEE_OTHER);
        }

        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

//-------------------------------------NOUVEL UTILISATEUR-----------------------------------------

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $email = (new Email())
            ->from('pppbigbud@gmail.com')
                ->to($user->getEmail())
                ->subject('Bienvenu chez 34 Burger vous pouvez maintenant retourner sur notre site pour commander :)')
//                ->text("Merci pour votre inscription {$user->getFirstname()}!")
            ;

            $mailer->send($email);

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 'error' => $error,
            'registrationForm' => $form->createView(),
            'userForm' => $formUser->createView(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
