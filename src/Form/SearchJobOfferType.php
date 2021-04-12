<?php


namespace App\Form;


use App\Data\SearchJobOffers;
use App\Entity\Cause;
use App\Entity\ContractType;
use App\Entity\Country;
use App\Entity\Department;
use App\Entity\LevelExperience;
use App\Entity\LevelStudy;
use App\Entity\Remuneration;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchJobOfferType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('country', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Country::class,
                'choice_label' => 'name',
                'placeholder' => '---Sélectionner une localisation---'
            ])
            ->add('department', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' =>Department::class,
                'choice_label' => 'name',
                'placeholder' => '---Sélectionner un département autorisé---'

             ])
            ->add('contractType', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => ContractType::class,
                'choice_label' => 'wording',
                'placeholder' => '---Sélectionner un type de contrat---'
            ])
            ->add('cause', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Cause::class,
                'choice_label' => 'text',
                'placeholder' => '---Sélectionner une cause---'
            ])
            ->add('remuneration', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Remuneration::class,
                'choice_label' => 'text',
                'placeholder' => '---Sélectionner une rémunération---'
            ])
            ->add('levelExperience', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => LevelExperience::class,
                'choice_label' => 'wording',
                'placeholder' => '---Sélectionner un niveau d\'expérience---'
            ])
            ->add('levelStudy', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => LevelStudy::class,
                'choice_label' => 'wording',
                'placeholder' => '---Sélectionner un niveau d\'étude---'
            ])
            ->add('freshness', CheckboxType::class, [
                'label'=>'Fraîcheur',
                'required'=>false
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchJobOffers::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}