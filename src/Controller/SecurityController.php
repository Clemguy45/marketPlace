<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;

class SecurityController extends AbstractController
{
    /**
     * This controller allow us to login using a form.
     */
    #[Route('/connexion', name: 'app_security_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('pages/security/login.html.twig',[
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * this controller allow us logout a user.
     *
     * @return void
     */
    #[Route('/deconnexion', name: 'app_security_logout', methods: ['GET'])]
    public function logout()
    {
        //Nothing to do here...
    }

    /**
     * this controller allow us to create a new user.
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/inscription', name: 'app_security_inscription', methods: ['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Votre inscription a bien été enregistrée.');
            return $this->redirectToRoute('acceuil.index');
        }
        return $this->render('pages/security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
