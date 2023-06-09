<?php
namespace App\DTO\PropertyListing;

use Symfony\Component\Validator\Constraints as Assert;

class PropertyListingInput
{
    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 128)]
    public $title;

    #[Assert\NotNull]
    #[Assert\Count(min: 1)]
    #[Assert\All(
        new Assert\Type('integer')
    )]
    #[Assert\Unique]
    public array $amenities;
}