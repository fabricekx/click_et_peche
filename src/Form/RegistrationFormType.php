<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Validator\Type\RepeatedTypeValidatorExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ],
                'row_attr' => ['class' => 'col-md-4 mt-3'],
                'constraints' => [
                    new NotBlank(['message' => 'Votre prénom doit être renseigné']),
                    new Length(['min' => 2, 'minMessage' => 'Votre prénom doit faire minimum 2 caractères']),
                ],

            ])

            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom'
                ],
                'row_attr' => ['class' => 'col-md-4 mt-3'],
                'constraints' => [
                    new NotBlank(['message' => 'Votre nom doit être renseigné']),
                    new Length(['min' => 2, 'minMessage' => 'Votre nom doit faire minimum 2 caractères']),
                ],

            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Votre email'
                ],
                'row_attr' => ['class' => 'col-md-4 mt-3'],
                'constraints' => [
                    new NotBlank(['message' => 'Votre email doit être renseigné']),
                    new Email(['message' => 'Veuillez renseigner un email valide!']),
                ],

            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'row_attr' => ['class' => 'col-md-4 mt-3'],
                'label' => 'Votre date de naissance'
            ])

            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'required' => false, // Vous pouvez modifier ceci en fonction de vos besoins
                'mapped' => true,
                'attr' => [
                    'accept' => 'image/*', // Permet de limiter le type de fichiers à des images
                    'max' => 500000, // Limite la taille maximale à 500ko 
                ],
            ])


            ->add('pseudo')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                // 'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Votre code postal'],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez renseigner un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Mot de Passe',
                    'row_attr' => [
                        'class' => 'col-md-6 mt-3'
                    ]
                ],
                'second_options' => [
                    'label' => 'Répétez le mot de passe',
                    'row_attr' => [
                        'class' => 'col-md-6 mt-3'
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
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
