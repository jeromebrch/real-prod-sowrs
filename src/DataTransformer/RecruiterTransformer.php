<?php


namespace App\DataTransformer;


use App\Entity\Recruiter;
use App\Repository\RecruiterRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class RecruiterTransformer implements DataTransformerInterface
{
    /**
     * @var RecruiterRepository
     */
    private $recruiterRepository;

    public function __construct(RecruiterRepository $recruiterRepository)
    {
        $this->recruiterRepository = $recruiterRepository;
    }

    /**
     * Transformation d'un objet en string
     * @param Recruiter $value
     * @return string
     */
    public function transform($value)
    {
        if (null == $value) {
            return '';
        }
        return $value->getLastname();
    }

    /**
     * Transformation d'un string en objet
     * @param string $value
     * @return Recruiter|null
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }
        $recruiter = $this->recruiterRepository->find($value);
        if (null == $recruiter) {
            throw new TransformationFailedException('Recruteur inconnu');
        }
        return $recruiter;
    }

}