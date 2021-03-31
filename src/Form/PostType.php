<?php

namespace App\Form;

use App\Entity\Cause;
use App\Entity\Picture;
use App\Entity\Post;
use App\Entity\Tag;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('presentationText', TextareaType::class, [
                'label' => 'Texte de présentation'
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Corps de l\'article'
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Tags à accrocher',
                'class' => Tag::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'text'
            ])
            ->add('picture', FileType::class, [
                'label' => 'Photo d\'illustration : ',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image conforme (JPEG, JPG ou PNG de taille max 2Mo',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
