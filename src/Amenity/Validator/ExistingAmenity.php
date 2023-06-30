<?php

namespace App\Amenity\Validator;

use Symfony\Component\Validator\Constraint;

class ExistingAmenity extends Constraint
{
    public string $message = 'Amenity with id {{ amenityId }} doesnt\' exist.';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }
}