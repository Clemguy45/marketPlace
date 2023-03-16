<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

 class UserPasswordType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('plainPassword', RepeatedType::class, [
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
        ->add('newPassword', PasswordType::class, [ 
            'label' => 'Nouveau Mot de passe', 
            'label_attr' => [
                'class' => 'form_label mt-4',
            ],
            'constraints' => [
                new Assert\NotBlank()
            ],
            'attr' => ['class' => 'form-control']
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Modifier',
            'attr' => [
                'class' => 'btn btn-primary mt-4',
            ]
        ]);
    }
 }