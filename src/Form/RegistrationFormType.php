<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pour le nom d'utilisateur
            ->add('username', null, [
                'label' => 'Nom utilisateur :',
                'attr' => ['class' => 'form-control'], 
            ])
            // Champ pour l'adresse e-mail
            ->add('email', null, [
                'label' => 'Adresse e-mail :',
                'attr' => ['class' => 'form-control'], 
            ])
            // Champ pour l'adresse de livraison
            ->add('deliveryAddress', TextType::class, [
                'label' => 'Adresse de livraison :',
                'attr' => ['class' => 'form-control'], 
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre adresse de livraison', // Message d'erreur
                    ]),
                ],
            ])
            // Case à cocher pour accepter les termes
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false, // Non mappé à l'entité
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos termes.', // Message d'erreur
                    ]),
                ],
            ])
            // Champ pour le mot de passe
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class, // Type du champ
                'mapped' => false, // Non mappé à l'entité
                'attr' => ['autocomplete' => 'new-password'], // Autocomplétion
                'first_options' => [
                    'label' => 'Mot de passe :',
                    'attr' => ['class' => 'form-control'], // Classe CSS
                    'constraints' => [
                        new NotBlank(['message' => 'Veuillez entrer un mot de passe']), // Message d'erreur
                        new Length(['min' => 4, 'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères']), // Validation de la longueur
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe :',
                    'attr' => ['class' => 'form-control'], // Classe CSS
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre.', // Message d'erreur 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Classe de données associée
        ]);
    }
}
