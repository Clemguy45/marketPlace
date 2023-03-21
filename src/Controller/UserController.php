<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
     
     /**
      * This controller allow us to edit a user profil.
      *
      * @param User $user
      * @param Request $request
      * @param EntityManagerInterface $manager
      * @return Response
      */
    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    #[Security('is_granted("ROLE_USER") and user === choosenUser')]
    public function edit(User $choosenUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('app_security_inscription');
        }
        $form = $this->createForm(UserType::class, $choosenUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($choosenUser, $form->getData()->getPlainPassword())) {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Votre profil a été modifiée avec succès!');
                return $this->redirectToRoute('acceuil.index');
            }
            $this->addFlash('warning', 'Votre mots de passe est incorrecte!');
        }
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * This controller allow us to edit a user password.
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route('/user/eidit/password/{id}', name: 'app_user_edit_password', methods: ['GET','POST'])]
    #[Security('is_granted("ROLE_USER") and user === choosenUser')]
    public function editPassword(User $choosenUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response {

        if (!$this->getUser()){
            return $this->redirectToRoute('app_security_inscription');
        }
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($choosenUser, $form->getData()['plainPassword'])) {
                $choosenUser->setCreatedAt(new \DateTimeImmutable());
                $choosenUser->setPlainPassword($form->getData()['newPassword']);
                $manager->persist($choosenUser);
                $manager->flush();
                $this->addFlash('success', 'Votre mot de passe a été modifiée avec succès!');
                return $this->redirectToRoute('acceuil.index');
            }
            $this->addFlash('warning', 'Votre mots de passe est incorrecte!');
        }

        return $this->render('pages/user/edit_password.html.twig', [ 
            'form' => $form->createView()
        ]);
    }
}
