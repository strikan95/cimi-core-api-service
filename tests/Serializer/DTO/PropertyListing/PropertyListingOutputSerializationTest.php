<?php
namespace App\tests\Serializer\DTO\PropertyListing;

use App\DTO\PropertyAmenity\PropertyAmenityOutput;
use App\DTO\PropertyListing\PropertyListingOutput;
use App\Services\Serializer\TransportObjectSerializer;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use function dd;


class PropertyListingOutputSerializationTest extends TestCase
{
    /** @test */
    public function property_listing_output_dto_serializes_to_json_string_correctly()
    {
        $correctJson = '{"id":"01870f58-b03e-7318-833d-4efb231e4591","created_at":"2023-03-23","title":"Some title","amenities":[{"id":1,"name":"Amenity 1"},{"id":2,"name":"Amenity 2"}]}';

        // Amenity DTOs
        $fooAmenity = new PropertyAmenityOutput();
        $fooAmenity->id = 1;
        $fooAmenity->name = 'Amenity 1';

        $barAmenity = new PropertyAmenityOutput();
        $barAmenity->id = 2;
        $barAmenity->name = 'Amenity 2';


        // Listing DTO
        $dto = new PropertyListingOutput();
        $dto->id = Uuid::fromString('01870f58-b03e-7318-833d-4efb231e4591');
        $dto->title = 'Some title';
        $dto->createdAt = DateTime::createFromFormat(TransportObjectSerializer::getDateTimeFormat(), '2023-03-23');
        $dto->amenities = [$fooAmenity, $barAmenity];


        $serializer = new TransportObjectSerializer();
        $json = $serializer->serialize(
            $dto,
            'json'
        );

        $this->assertSame($correctJson, $json);
    }
}