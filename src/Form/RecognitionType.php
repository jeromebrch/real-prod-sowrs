<?php

namespace App\Form;

use App\DataTransformer\CauseTransformer;
use App\DataTransformer\RecruiterTransformer;
use App\Entity\Recognition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class RecognitionType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var RecruiterTransformer
     */
    private $recruiterTransformer;
    /**
     * @var CauseTransformer
     */
    private $causeTransformer;

    public function __construct(EntityManagerInterface $em, RecruiterTransformer $recruiterTransformer,
                                CauseTransformer $causeTransformer)
    {
        $this->em = $em;
        $this->recruiterTransformer = $recruiterTransformer;
        $this->causeTransformer = $causeTransformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cause', HiddenType::class)
            ->add('recruiter', HiddenType::class)
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Nom de la reconnaissance :',
            ]);

        // il faut convertir "recruiter" en objet
        $builder->get('recruiter')->addModelTransformer($this->recruiterTransformer);
        // il faut convertir "action" en objet
        $builder->get('cause')->addModelTransformer($this->causeTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recognition::class,
            'csrf_protection' => false // il faut retirer la protection CSRF car on traite en Ajax
        ]);
    }

}
