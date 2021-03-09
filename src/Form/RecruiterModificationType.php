<?php

namespace App\Form;

use App\Entity\Cause;
use App\Entity\LegalStatus;
use App\Entity\Recruiter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class RecruiterModificationType extends AbstractType
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
                    'placeholder' => 'A propos de votre entité']
            ])

            ->add('entityName', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom de votre entité']
            ])
            ->add('reasonToBe', TextareaType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Raison d\'être de votre entité']
            ])
            ->add('activityNumber', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Numéro d\'identification (SIRET, RNA)']
            ])
            ->add('function', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'fonction']
            ])
            ->add('legalStatus', EntityType::class, [
                'class'=> LegalStatus::class,
                'choice_label' => 'text',
                'required' => false,
                'label' => false,
                'placeholder' => '---Sélectionner un statut légal---'
            ])
            ->add('mainCause', EntityType::class, [
                'class' => Cause::class,
                'choice_label' => 'text',
                'label' => false,
                'required' => true,
                'placeholder' => '---Sélectionner une cause principale---'
            ])
            ->add('secondaryCauses', EntityType::class, [
                'class' => Cause::class,
                'choice_label' => 'text',
                'multiple' => true,
                'required' => false,
                'label' => false,
                'placeholder' => '---Sélectionner des causes secondaires---'
            ])
            ->add('socialNetwork', SocialNetworkType::class, [

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recruiter::class,
        ]);
    }

}
