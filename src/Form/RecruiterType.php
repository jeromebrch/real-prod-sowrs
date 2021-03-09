<?php

namespace App\Form;

use App\Entity\Cause;
use App\Entity\Recruiter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RecruiterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre email'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre nom'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre téléphone'
                ]
            ])
            ->add('function', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre fonction'
                ]
            ])
            ->add('entityName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom de l\'organisation'
                ]
            ])
            ->add('activityNumber', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Numéro d\'activité (SIRET, RNA)'
                ]
            ])
            ->add('legalStatus', null, [
                'choice_label' => 'text',
                'required' => true,
                'label' => false,
                'placeholder' => '---Sélectionner un statut juridique---'
            ])
            ->add('mainCause', EntityType::class, [
                'class' => Cause::class,
                'choice_label' => 'text',
                'label' => false,
                'required' => true,
                'placeholder' => '---Sélectionner une cause---'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'label' => 'password',
                'type' => PasswordType::class,
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => false, 'attr' => ['placeholder' => 'Mot de passe']],
                'second_options' => ['label' => false, 'attr' => ['placeholder' => 'Répéter le mot de passe']],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe !',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de pass doit comporter au mooins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Merci d\'accepter les conditions',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recruiter::class,
        ]);
    }
}
