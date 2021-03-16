<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('email', EmailType::class,
                ['constraints' => [
                 new NotBlank([
                    'message' => 'Merci d\'entrer votre adresse mail.'])
                ]])
            ->add('telephone')
            ->add('message', TextareaType::class, [
                'attr' =>['rows' =>6],
                'constraints' => [
                new NotBlank([
                    'message' => 'Merci d\'entrer votre message.'])
                 ]])
           ->add('fichier', FileType::class, [
                'label' => 'Ajouter une pièce jointe'
            ])
            ->add('autreFichier', FileType::class, [
                'label' => false
            ]);
    }





    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            ]);
    }
}
