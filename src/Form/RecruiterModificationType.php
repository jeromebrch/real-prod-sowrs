<?php

namespace App\Form;

use App\Entity\Cause;

use App\Entity\LegalStatus;
use App\Entity\Recruiter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


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
            ->add('city', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville']
            ])
            ->add('country', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Pays']
            ])
            ->add('carbonFootPrint', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Empreinte carbone']
            ])
            //Upload the carbon footprint proof in PDF
            ->add('carbonFootPrintProof', FileType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Justificatif',
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Merci d\'uploader un fichier au format PDF',
                    ])
                ],
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

            ])
            /*
            ->add('commitments', TextType::class, [
            'required' => false,
            'label' => 'Engagements',
        ])
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recruiter::class,
        ]);
    }

}
