<?php

namespace App\DTO\Request\Property;

use DateTime;

class GetPropertyDto
{
    public ?int $id;
    public DateTime $createdAt;

    public string $title;

    public string $description;

    public array $amenities = [];
}