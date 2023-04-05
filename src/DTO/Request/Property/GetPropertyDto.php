<?php

namespace App\DTO\Request\Property;

use App\DTO\DtoResourceInterface;
use DateTime;

class GetPropertyDto implements DtoResourceInterface
{
    public ?int $id;
    public DateTime $createdAt;

    public string $title;

    public string $description;

    public array $amenities = [];
}