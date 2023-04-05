<?php

namespace App\Controller;

use App\DTO\Builder\PropertyListingBuilder;
use App\DTO\PropertyListing\PropertyListingInput;
use App\DTO\PropertyListing\PropertyListingOutput;
use App\Entity\PropertyAmenity;
use App\Entity\PropertyListing;
use App\Repository\PropertyListingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Http\Discovery\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PropertyListingController extends AbstractController
{
    public function __construct
    (
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $em,
        private readonly PropertyListingBuilder $listingBuilder
    )
    {
    }
    #[Route('/api/v1/listings/{id}', name: 'api.listing.get.one', methods: ['GET'])]
    public function findById(int $id): JsonResponse
    {
        /** @var PropertyListing $entity */
        $entity = $this->em->getRepository(PropertyListing::class)->findOneBy(['id' => $id]);
        if(!$entity)
        {
            throw $this->createNotFoundException('Listing not found');
        }

        $dto = $this->listingBuilder->buildDto($entity);
        return $this->json($dto);
    }

    #[Route('/api/v1/listings', name: 'api.listing.add', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $dto = $this->serializer->deserialize($content, PropertyListingInput::class, 'json');

        $errors = $this->validator->validate($dto);
        if(count($errors) > 0)
        {
            //Throw error
        }

        $entity = $this->listingBuilder->buildEntity($dto);

        $this->em->persist($entity);
        $this->em->flush();

        return $this->json([], 201, ['Location' => '/listings/'.$entity->getId()]);
    }
}
