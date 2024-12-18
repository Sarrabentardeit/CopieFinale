<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use App\Entity\User;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d’utilisateur',
                'attr' => ['placeholder' => 'Entrez votre nom d’utilisateur'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un nom d’utilisateur.']),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Le nom d’utilisateur doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom d’utilisateur ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Adresse e-mail',
                'attr' => ['placeholder' => 'Entrez votre adresse e-mail'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer une adresse e-mail.']),
                    new Email(['message' => 'Veuillez entrer une adresse e-mail valide.']),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'Patient' => 'ROLE_PATIENT',
                    'Docteur' => 'ROLE_DOCTOR',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['placeholder' => 'Entrez un mot de passe sécurisé'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe.']),
                    new Length([
                        'min' => 6,
                        'max' => 4096,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('specialization', TextType::class, [
                'label' => 'Spécialisation',
                'required' => false,
                'mapped' => false, // Indique que ce champ n'est pas lié directement à l'entité User
                'attr' => ['placeholder' => 'Entrez votre spécialisation'],
            ])
            ->add('licenseNumber', TextType::class, [
                'label' => 'Numéro de Licence',
                'required' => false,
                'mapped' => false, // Indique que ce champ n'est pas lié directement à l'entité User
                'attr' => ['placeholder' => 'Entrez votre numéro de licence'],
            ])
            ->add('dob', DateType::class, [
                'label' => 'Date de Naissance',
                'widget' => 'single_text',
                'required' => false,
                'mapped' => false, // Indique que ce champ n'est pas lié directement à l'entité User
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'required' => false,
                'mapped' => false, // Indique que ce champ n'est pas lié directement à l'entité User
                'attr' => ['placeholder' => 'Entrez votre adresse'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les termes',
                'constraints' => [
                    new IsTrue(['message' => 'Vous devez accepter les termes.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
