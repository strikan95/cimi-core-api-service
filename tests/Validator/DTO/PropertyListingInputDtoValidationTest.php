<?php
namespace App\Tests\Validator\DTO;

use App\DTO\PropertyListing\PropertyListingInput;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertyListingInputDtoValidationTest extends KernelTestCase
{
    /** @test */
    public function test()
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $validator = $container->get('myvalidator');


        $dto = new PropertyListingInput();
        $dto->title = 'Some title';
        $dto->description = 'Some description';
        $dto->amenities = [1, 2];

        $errors = $validator->validate($dto);

        $this->assertCount(0, $errors);
    }
}