<?php


namespace App\Form;


use App\Data\SearchCandidate;
use App\Entity\Cause;
use App\Entity\ContractType;
use App\Entity\Country;
use App\Entity\Department;
use App\Entity\Language;
use App\Entity\LevelExperience;
use App\Entity\LevelStudy;
use App\Entity\Remuneration;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchCandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('txt', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('localization', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Country::class,
                'choice_label' => 'name',
                'placeholder' => '---Pays de résidence---'
            ])
            ->add('remuneration', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Remuneration::class,
                'choice_label' => 'text',
                'placeholder' => '---Rémuneration---'
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
            ->add('levelStudy', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => LevelStudy::class,
                'choice_label' => 'wording',
                'placeholder' => '---Niveau d\'étude---'

            ])
            ->add('levelExp', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => LevelExperience::class,
                'choice_label' => 'wording',
                'placeholder' => '---Niveau d\'expérience---'

            ])
            ->add('authorizedCountry', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Country::class,
                'choice_label' => 'name',
                'placeholder' => '---Autorisé à travailler en---'
            ])
            ->add('language', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Language::class,
                'choice_label' => 'name',
                'placeholder' => '---Langue du candidat---'
            ])
            ->add('telecommute', CheckboxType::class, [
                'label' => "Télétravailleur",
                'required' => false

            ])
            ->add('department', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' =>Department::class,
                'choice_label' => 'name',
                'placeholder' => '---Localisation du poste recherché---'
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchCandidate::class
        ]);
    }
}