<?php


namespace App\DataTransformer;

use App\Entity\Cause;
use App\Repository\CauseRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CauseTransformer implements DataTransformerInterface
{

    /**
     * @var CauseRepository
     */
    private $causeRepository;

    public function __construct(CauseRepository $causeRepository)
    {
        $this->causeRepository = $causeRepository;
    }

    /**
     * Trasnformation d'un objet en string
     * @param Cause $value
     * @return string
     */
    public function transform($value): string
    {
        if (null == $value) {
            return '';
        }
        return $value->getText();
    }


    /**
     * Transformation d'un string en objet
     * @param string $value
     * @return Cause|null
     */
    public function reverseTransform($value): ?Cause
    {
        if (!$value) {
            return null;
        }
        $cause = $this->causeRepository->find($value);
        if (null == $cause) {
            throw new TransformationFailedException('Cause inconnue');
        }
        return $cause;
    }

}