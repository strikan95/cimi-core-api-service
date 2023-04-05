<?php
namespace App\Tests\Serializer\DTO\PropertyListing;

use App\DTO\Request\Amenity\GetAmenityDto;
use App\DTO\Request\Property\GetPropertyDto;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class PropertyListingOutputSerializationTest extends KernelTestCase
{

    private $serializer;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->serializer = $container->get('serializer');
    }

    /** @test */
    public function property_listing_output_dto_serializes_to_json_string_correctly()
    {
        $correctJson = '{"id":1,"created_at":"2023-03-23","title":"Some title","description":"Some description","amenities":[{"id":1,"name":"Amenity 1"},{"id":2,"name":"Amenity 2"}]}';

        // Amenity DTOs
        $fooAmenity = new GetAmenityDto();
        $fooAmenity->id = 1;
        $fooAmenity->name = 'Amenity 1';

        $barAmenity = new GetAmenityDto();
        $barAmenity->id = 2;
        $barAmenity->name = 'Amenity 2';


        // Listing DTO
        $dto = new GetPropertyDto();
        $dto->id = 1;
        $dto->title = 'Some title';
        $dto->description = 'Some description';
        $dto->createdAt = DateTime::createFromFormat('Y-m-d', '2023-03-23');
        $dto->amenities = [$fooAmenity, $barAmenity];


        $json = $this->serializer->serialize(
            $dto,
            'json'
        );

        $this->assertSame($correctJson, $json);
    }
}