<?php

namespace App\DTO\Request\Amenity;

use App\DTO\DtoResourceInterface;

class GetAmenityDto implements DtoResourceInterface
{
    public int $id;
    public string $name;
}