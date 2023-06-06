<?php

namespace App\tests\Serializer\DTO\PropertyListing;

use App\DTO\PropertyListing\PropertyListingInput;
use App\Services\Serializer\TransportObjectSerializer;
use PHPUnit\Framework\TestCase;

class PropertyListingInputSerializationTest extends TestCase
{
    /** @test */
    public function json_string_deserializes_to_property_listing_input_dto_object_correctly()
    {
        $json = '{"title":"Some title","amenities":[1,2]}';

        $serializer = new TransportObjectSerializer();

        /** @var PropertyListingInput $dto */
        $dto = $serializer->deserialize($json, PropertyListingInput::class, 'json');

        $this->assertEquals('Some title', $dto->title);
        $this->assertContains(2, $dto->amenities);
        $this->assertEquals(1, $dto->amenities[0]);
        $this->assertEquals(2, $dto->amenities[1]);
    }
}