<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Nom / Prenom',
                'label_attr' => [
                    'class' => 'form_label mt-4'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'constraints' => [ 
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2,'max' => 50]),
    
            ]])
            ->add('pseudo', TextType::class,
            [
                'label' => 'Pseudo (Facultatif)',
                'label_attr' => [
                    'class' => 'form_label mt-4',
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]])
            ->add('email', EmailType::class, 
            [
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form_label mt-4',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '180'
                ],
                'constraints' => [
                    new Assert\Email(),
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2,'max' => 180])
                ]])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'label_attr' => [
                        'class' => 'form_label mt-4',
                    ],
                    'attr' => ['class' => 'form-control']
            ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'label_attr' => [
                        'class' => 'form_label mt-4',
                    ],
                    'attr' => ['class' => 'form-control']],
                'invalid_message' => 'Veuillez saisir le même mot de passe',

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Inscription',
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
