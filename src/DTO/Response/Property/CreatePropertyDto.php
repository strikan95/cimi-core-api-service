<?php

namespace App\DTO\Response\Property;

use App\DTO\DtoResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePropertyDto implements DtoResourceInterface
{
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 128)]
    public $title;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(min: 15, max: 255)]
    public $description;

    #[Assert\NotNull]
    #[Assert\Count(min: 1)]
    #[Assert\All(
        new Assert\Type('integer')
    )]
    #[Assert\Unique]
    public array $amenities;
}