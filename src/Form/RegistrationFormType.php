<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control'], // Appliquer la même taille pour tous les champs
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an email',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your email should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control', 'style' => 'margin-bottom: 10px;'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a name',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your name should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Conditions générales',
                'label_attr' => ['class' => 'form-check-label'],
                'row_attr' => ['class' => 'mt-3'], // Espacement autour du label
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'attr' => ['class' => 'form-check-input', 'style' => 'margin-bottom: 10px;'], // Espacement autour de la checkbox
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Password', // Label pour le champ de mot de passe
                'label_attr' => ['class' => 'mt-3'], // Espacement autour du label
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'], // Appliquer la même taille pour tous les champs
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Confirm Password', // Label pour la confirmation du mot de passe
                'label_attr' => ['class' => 'mt-3'], // Espacement autour du label
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'], // Uniformiser la taille
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please confirm your password',
                    ]),
                ],
            ])
            ->add('path', ChoiceType::class, [
                'label' => 'Groupe',
                'label_attr' => ['class' => 'mt-3'], // Espacement autour du label
                'attr' => ['class' => 'form-control'], // Uniformiser la taille
                'choices' => [
                    'premier groupe' => 'group1',
                    'second groupe' => 'group2',
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
