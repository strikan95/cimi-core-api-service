<?php

namespace App\tests\Serializer\DTO\PropertyAmenity;

use App\DTO\PropertyAmenity\PropertyAmenityOutput;
use App\Service\Serializer\TransportObjectSerializer;
use PHPUnit\Framework\TestCase;

class PropertyAmenityOutputSerializationTest extends TestCase
{
    /** @test */
    public function property_amenity_output_dto_serializes_to_json_string_correctly()
    {
        $correctJson = '{"id":1,"name":"Test amenity"}';

        $dto = new PropertyAmenityOutput();
        $dto->id = 1;
        $dto->name = 'Test amenity';

        $serializer = new TransportObjectSerializer();

        $json = $serializer->serialize($dto, 'json');

        $this->assertSame($correctJson, $json);
    }
}