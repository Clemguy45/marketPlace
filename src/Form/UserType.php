<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
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
            ->add('plainPassword', PasswordType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'form_label mt-4',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modification',
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
