<?php

namespace App\Tests\Serializer\DTO\PropertyListing;

use App\DTO\Response\Property\CreatePropertyDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertyListingInputSerializationTest extends KernelTestCase
{
    private $serializer;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->serializer = $container->get('serializer');
    }

    /** @test */
    public function json_string_deserializes_to_property_listing_input_dto_object_correctly()
    {
        $json = '{"title":"Some title","amenities":[1,2]}';

        /** @var CreatePropertyDto $dto */
        $dto = $this->serializer->deserialize($json, CreatePropertyDto::class, 'json');

        $this->assertEquals('Some title', $dto->title);
        $this->assertContains(2, $dto->amenities);
        $this->assertEquals(1, $dto->amenities[0]);
        $this->assertEquals(2, $dto->amenities[1]);
    }
}