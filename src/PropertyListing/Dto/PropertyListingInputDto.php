<?php

namespace App\PropertyListing\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class PropertyListingInputDto
{
    #[Groups(['update'])]
    public ?int $id;

    #[Groups(['create', 'update'])]
    public ?string $title;

    #[Groups(['create', 'update'])]
    public ?string $description;

    #[Groups(['create', 'update'])]
    public ?int $price;

    #[Groups(['create', 'update'])]
    public ?string $lat;

    #[Groups(['create', 'update'])]
    public ?string $lon;

    #[Groups(['create', 'update'])]
    public ?array $amenities;
}