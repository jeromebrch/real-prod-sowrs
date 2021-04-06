<?php

namespace App\Form;

use App\Entity\BusinessProfile;
use App\Entity\Candidate;
use App\Entity\Cause;
use App\Entity\Country;
use App\Entity\LevelExperience;
use App\Entity\LevelStudy;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse e-mail']
            ])
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom']
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom']
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone']
            ])
            ->add('about', TextareaType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'À propos de vous']
            ])
            ->add('authorizedCountry', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'Autorisé(e) à travailler en'
            ])
            ->add('currentRole', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Poste Actuel']
            ])
            ->add('inspiration', TextareaType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'La phrase qui vous inspire']
            ])
            ->add('levelStudy', EntityType::class, [
                'class' => LevelStudy::class,
                'choice_label' => 'wording',
                'label' => false,
                'required' => true,
                'placeholder' => 'Niveau d\'étude'
            ])
            ->add('levelExperience', EntityType::class, [
                'class' => LevelExperience::class,
                'choice_label' => 'wording',
                'label' => false,
                'required' => true,
                'placeholder' => 'Niveau d\'expérience'
            ])
            ->add('businessProfile', EntityType::class, [
                'class' => BusinessProfile::class,
                'choice_label' => 'wording',
                'label' => false,
                'required' => true,
                'placeholder' => 'Secteur de formation'
            ])->add('mainCause', EntityType::class, [
                'class' => Cause::class,
                'choice_label' => 'text',
                'label' => false,
                'required' => true,
                'placeholder' => '---Sélectionner une cause principale---'
            ])
            ->add('socialNetwork', SocialNetworkType::class)

            ->add('jobSearch', JobSearchType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }

}
