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
                'placeholder' => '---Pays---'
            ])
            ->add('department', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' =>Department::class,
                'choice_label' => 'name',
                'placeholder' => '---Département---'

             ])
            ->add('contractType', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => ContractType::class,
                'choice_label' => 'wording',
                'placeholder' => '---Type de contrat---'
            ])
            ->add('cause', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Cause::class,
                'choice_label' => 'text',
                'placeholder' => '---Cause défendue---'
            ])
            ->add('remuneration', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Remuneration::class,
                'choice_label' => 'text',
                'placeholder' => '---Rémunération---'
            ])
            ->add('levelExperience', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => LevelExperience::class,
                'choice_label' => 'wording',
                'placeholder' => '---Niveau d\'expérience---'
            ])
            ->add('levelStudy', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => LevelStudy::class,
                'choice_label' => 'wording',
                'placeholder' => '---Niveau d\'étude---'
            ])
            ->add('telecommute', CheckboxType::class, [
                'label' => "Télétravail accepté",
                'required' => false

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