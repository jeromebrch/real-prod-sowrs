<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;


class ApplyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('body', TextareaType::class, [
                'attr' =>['rows' =>16],
                'label'=> false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer votre message.'
                    ])
                ]])
            ->add('cvFile' ,FileType::class,[
                'label' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'application/pdf'
                        ],
                        'mimeTypesMessage' => 'Veuillez sélectionner un PDF de max 1Mo',
                    ])
                ]
                ])
            ->add('mediaFile', FileType::class, [
                'label' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'application/pdf'
                        ],
                        'mimeTypesMessage' => 'Veuillez sélectionner un PDF de max 1Mo',
                    ])
                ]
                ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

            // Configure your form options here

            'data_class' => Message::class,

        ]);
    }
}
