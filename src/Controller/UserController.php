<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function edit(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('app_security_inscription');
        }
        if($this->getUser() !== $user){
            return $this->redirectToRoute('acceuil.index');
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())) {
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

    #[Route('/user/eidit/password/{id}', name: 'app_user_edit_password', methods: ['GET','POST'])]
    public function editPassword(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response {
        if (!$this->getUser()){
            return $this->redirectToRoute('app_security_inscription');
        }
        if($this->getUser()!== $user){
            return $this->redirectToRoute('acceuil.index');
        }
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($user, $form->getData()['plainPassword'])) {
                // la fonction preupdate ne foctionne pas.
                $user->setPassword($hasher->hashPassword($user, $form->getData()['newPassword']));
                $manager->persist($user);
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
