<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstName', TextType::class, [
            'label' => 'Prénom',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Votre prénom'
            ],
            'row_attr' => ['class' => 'form-control col-md-4 mt-3'],
            'constraints' => [
                new NotBlank(['message' => 'Votre prénom doit être renseigné']),
                new Length(['min' => 2, 'minMessage' => 'Votre prénom doit faire minimum 2 caractères']),
            ],

        ])

        ->add('lastName', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Votre nom'
            ],
            'row_attr' => ['class' => 'form-control col-md-4 mt-3'],
            'constraints' => [
                new NotBlank(['message' => 'Votre nom doit être renseigné']),
                new Length(['min' => 2, 'minMessage' => 'Votre nom doit faire minimum 2 caractères']),
            ],

        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Votre email'
            ],
            'row_attr' => ['class' => ' form-control col-md-4 mt-3'],
            'constraints' => [
                new NotBlank(['message' => 'Votre email doit être renseigné']),
                new Email(['message' => 'Veuillez renseigner un email valide!']),
            ],

        ])
        ->add('birthday', DateType::class, [
            'attr' => ['class' => 'form-control',],
            'widget' => 'single_text',
            'row_attr' => ['class' => 'form-control col-md-4 mt-3'],
            'label' => 'Votre date de naissance'
        ])

        ->add('avatar', FileType::class, [
            'label' => 'Modifier votre Avatar',

            'required' => false, // Vous pouvez modifier ceci en fonction de vos besoins
            'mapped' => true,
            'attr' => [
                'class' => 'form-control',
                'accept' => 'image/*', // Permet de limiter le type de fichiers à des images
                'max' => 500000, // Limite la taille maximale à 500ko 
            ],
        ])

        ->add('phoneNumber', TelType::class, [
            'label' => 'Numéro de téléphone',
            'required' => false, // Vous pouvez modifier ceci en fonction de vos besoins

            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Votre numéro de téléphone'
            ],
            'row_attr' => ['class' => 'form-control col-md-6'],
            'constraints' => [
                new NotBlank(['message' => 'Votre numéro de téléphone doit être renseigné']),
                new Length(['min' => 10, 'max' => 10, 'exactMessage' => 'Votre numéro de téléphone doit faire 10 caractères']),
            ],
        ])

        ->add(
            'pseudo',
            TextType::class,
            [
                'label' => 'Pseudo',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre pseudo'
                ],
                'row_attr' => ['class' => 'form-control col-md-4 mt-3'],
                'constraints' => [
                    new NotBlank(['message' => 'Votre Pseudo doit être renseigné']),
                    new Length(['min' => 2, 'minMessage' => 'Votre pseudo doit faire minimum 2 caractères']),
                ],
            ]
            );
       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
