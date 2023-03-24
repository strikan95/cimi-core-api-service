<?php
namespace App\DTO\PropertyListing;

use DateTime;
use Symfony\Component\Uid\Uuid;

class PropertyListingOutput
{
    public ?Uuid $id;
    public DateTime $createdAt;

    public string $title;

    public array $amenities;
}