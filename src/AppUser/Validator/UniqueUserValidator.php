<?php

namespace App\AppUser\Validator;

use App\AppUser\Dto\AppUser as AppUserDto;
use App\AppUser\Repository\AppUserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserValidator extends ConstraintValidator
{

    public function __construct
    (
        private readonly AppUserRepository $appUserRepository,
    )
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if(!$value instanceof AppUserDto)
        {
            throw new \LogicException('Unique user validator must be used with AppUser DTO class');
        }

        $userIdentifier = $value->getUserIdentifier();

        if(!$userIdentifier)
        {
            throw new \LogicException('User identifier is not set.');
        }

        $isUidTaken = !($this->appUserRepository->findOneBy(['userIdentifier' => $userIdentifier]) === null);

        if(!$isUidTaken)
        {
            return;
        }

        $this
            ->context
            ->buildViolation($constraint->message)
            ->setParameter('{{ userIdentifier }}', $userIdentifier)
            ->atPath('appUser.userIdentifier')
            ->addViolation()
        ;
    }
}