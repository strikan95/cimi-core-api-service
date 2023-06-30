<?php

namespace App\Amenity\Validator;

use App\Amenity\Repository\AmenityRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ExistingAmenityValidator extends ConstraintValidator
{

    public function __construct(
        private readonly AmenityRepository $amenityRepository
    )
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ExistingAmenity) {
            throw new UnexpectedTypeException($constraint, ExistingAmenity::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_int($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'integer');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        if($this->amenityRepository->findOneBy(['id' => $value]) !== null)
        {
            // Exists -- all good
            return;
        }

        $this
            ->context
            ->buildViolation($constraint->message)
            ->setParameter('{{ amenityId }}', $value)
            ->atPath('amenity.id')
            ->addViolation()
        ;
    }
}