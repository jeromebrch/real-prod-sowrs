<?php

namespace App\Form;

use App\Entity\BusinessProfile;
use App\Entity\Candidate;
use App\Entity\Department;
use App\Entity\LevelExperience;
use App\Entity\LevelStudy;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre email'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un email !'
                    ])
                ]
            ])
            ->add('levelStudy', EntityType::class, [
                'class' => LevelStudy::class,
                'choice_label' => 'wording',
                'label' => false,
                'required' => false,
                'placeholder' => '---Sélectionner un niveau d\'étude---'
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions générales d\'utilisation',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Merci d\'accepter les conditions',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un prénom !'
                    ])
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre nom'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un nom !'
                    ])
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre téléphone'
                ]
            ])
            ->add('department', EntityType::class,[
                'class'=> Department::class,
                'choice_label'=> 'name',
                'label'=> false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Département'
                ]
            ])
            ->add('currentJob', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Poste actuel'
                ]
            ])
            ->add('levelExperience', EntityType::class, [
                'class' => LevelExperience::class,
                'choice_label' => 'wording',
                'label' => false,
                'required' => false,
                'placeholder' => '---Sélectionner un niveau d\'expérience---'
            ])
            ->add('businessProfile', EntityType::class, [
                'class' => BusinessProfile::class,
                'choice_label' => 'wording',
                'label' => false,
                'required' => false,
                'placeholder' => '---Sélectionner un profil métier---'
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
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
}
