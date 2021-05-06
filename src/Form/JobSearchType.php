<?php

namespace App\Form;

use App\Entity\Cause;
use App\Entity\ContractType;
use App\Entity\Country;
use App\Entity\Department;
use App\Entity\JobSearch;
use App\Entity\Region;
use App\Entity\Remuneration;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'required' => true,
                'label' => false,
                'placeholder' => 'Localisation'
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'région'
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'département'
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville'
                ]
            ])
            ->add('jobTitle', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Intitulé du poste recherché']
            ])
            ->add('contractType', EntityType::class, [
                'class' => ContractType::class,
                'choice_label' => 'wording',
                'required' => true,
                'label' => false,
                'placeholder' => 'Type de contrat'
            ])
            ->add('cause', EntityType::class, [
                'class' => Cause::class,
                'choice_label' => 'text',
                'label' => false,
                'placeholder' => 'Cause'
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name',
                'label' => false,
                'placeholder' => 'Département souhaité'
            ])
            ->add('telecommute', CheckboxType::class, [
                'label' => "Télétravail accepté",
                'required' => false

            ])
            ->add('desiredRemuneration', EntityType::class, [
            'class'=>Remuneration::class,
                'choice_label'=>'text',
                'label'=>false,
                'placeholder' => 'Rémunération souhaitée'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobSearch::class,
        ]);
    }
}
