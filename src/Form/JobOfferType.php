<?php

namespace App\Form;

use App\Entity\BusinessProfile;
use App\Entity\ContractType;
use App\Entity\Country;
use App\Entity\Department;
use App\Entity\JobOffer;
use App\Entity\LevelExperience;
use App\Entity\LevelStudy;
use App\Entity\Region;
use App\Entity\Remuneration;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class JobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Titre de l\'annonce F/H'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Descriptif de l\'offre',
                ],
                'constraints' => [
                    new Length([
                        'min' => 150,
                        'minMessage' => 'Ce champ doit comporter minimum 150 caractères.'
                    ])
                ]
            ])
//            ->add('tags', TextareaType::class, [
//                'label' => false,
//                'required' => false,
//                'attr' => [
//                    'placeholder' => 'Tags (séparés par une virgule)'
//                ]
//            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville'
                ]
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'Région'
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'Département'
            ])
            ->add('category', EntityType::class, [
                'class' => BusinessProfile::class,
                'choice_label' => 'wording',
                'label' => false,
                'placeholder' => 'Catégorie'
            ])
            ->add('remuneration', EntityType::class, [
                'class' => Remuneration::class,
                'choice_label' => 'text',
                'label' => false,
                'placeholder' => 'Rémunération'
            ])
            ->add('contractType', EntityType::class, [
                'class' => ContractType::class,
                'choice_label' => 'wording',
                'label' => false,
                'placeholder' => 'Type de contrat'
            ])
            ->add('levelExperience', EntityType::class, [
                'class' => LevelExperience::class,
                'choice_label' => 'wording',
                'label' => false,
                'placeholder' => 'Niveau d\'expérience'
            ])
            ->add('levelStudy', EntityType::class, [
                'class' => LevelStudy::class,
                'choice_label' => 'wording',
                'label' => false,
                'placeholder' => 'Niveau d\'étude'
            ])
            ->add('telecommuting', CheckboxType::class, [
                'label' => "Télétravail accepté",
                'required' => false
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => false,
                'placeholder' => 'Pays'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobOffer::class,
        ]);
    }
}
