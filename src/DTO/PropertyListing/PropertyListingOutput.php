<?php
namespace App\DTO\PropertyListing;

use DateTime;
use Symfony\Component\Uid\Uuid;

class PropertyListingOutput
{
    public ?int $id;
    public DateTime $createdAt;

    public string $title;

    public string $description;

    public array $amenities = [];
}