<?php

namespace App\Tests\Serializer\DTO\PropertyAmenity;

use App\DTO\Request\Amenity\GetAmenityDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertyAmenityOutputSerializationTest extends KernelTestCase
{
    private $serializer;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->serializer = $container->get('serializer');
    }

    /** @test */
    public function property_amenity_output_dto_serializes_to_json_string_correctly()
    {
        $correctJson = '{"id":1,"name":"Test amenity"}';

        $dto = new GetAmenityDto();
        $dto->id = 1;
        $dto->name = 'Test amenity';

        $json = $this->serializer->serialize($dto, 'json');

        $this->assertSame($correctJson, $json);
    }
}