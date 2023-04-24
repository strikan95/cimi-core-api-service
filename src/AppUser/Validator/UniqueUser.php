<?php

namespace App\AppUser\Validator;

use Symfony\Component\Validator\Constraint;

class UniqueUser extends Constraint
{
    public string $message = 'User with identifier {{ userIdentifier }} already registered.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }
}