<?php

namespace App\Tests\Controller;

use App\Entity\Property;
use App\Entity\Amenity;
use App\Tests\BaseApiTestCase;
use App\Tests\Fixtures\PropertyAmenityTestFixture;

class PropertyListingControllerTest extends BaseApiTestCase
{
    /** @test */
    public function test_property_listing_creates_and_returns_OK_with_location_header()
    {
        $input = [
            'title' => 'Test property',
            'description' => 'Test description of a property',
            'amenities' => [
                $this->fixtureReferences[Amenity::class][PropertyAmenityTestFixture::WIFI_AMENITY_REFERENCE]->getId(),
                $this->fixtureReferences[Amenity::class][PropertyAmenityTestFixture::WASHING_MACHINE_AMENITY_REFERENCE]->getId()
            ]
        ];

        $this->client->request(
            method: 'POST',
            uri: '/api/v1/listings',
            content: json_encode($input)
        );

        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201, $response);
        $this->assertResponseHasHeader('Location', $response);
        $this->assertStringStartsWith('/listings', $response->headers->get('Location'));

        // Assert that its in the db
        $entity = $this->entityManager->getRepository(Property::class)->findOneBy(['id' => 4]);
        $this->assertNotNull($entity);
    }
}