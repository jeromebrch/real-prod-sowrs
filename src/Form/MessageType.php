<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('subject', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Sujet...'
                ]
            ])
            ->add('body', TextareaType::class, [
                'label'=> false,
                'attr' =>[
                    'rows' =>12,
                    'placeholder' => 'Votre message...'
                    ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer votre message.'])
                ]])
            ->add('cvFile' ,FileType::class,[
                'label' => 'Votre CV',
                'required' => false,
            ])
            ->add('mediaFile', FileType::class, [
                'label' => 'Votre document',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
