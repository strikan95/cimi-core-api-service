<?php

namespace App\Controller;

use App\DTO\PropertyListing\PropertyListingInput;
use App\DTO\PropertyListing\PropertyListingOutput;
use App\Entity\PropertyAmenity;
use App\Entity\PropertyListing;
use App\Repository\PropertyListingRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        private readonly EntityManagerInterface $em
    )
    {
    }
    #[Route('/api/v1/listings/{id}', name: 'api.listing.get.one', methods: ['GET'])]
    public function findById(int $id): JsonResponse
    {
        /** @var PropertyListing $entity */
        $entity = $this->em->getRepository(PropertyListing::class)->findOneBy(['id' => $id]);
        if ($entity) {
            $dto = new PropertyListingOutput();
            $dto->id = $entity->getId();
            $dto->title = $entity->getTitle();
            $dto->description = $entity->getDescription();
            $dto->createdAt = $entity->getCreatedAt();

            foreach ($entity->getAmenities() as $amenity)
            {
                $dto->amenities[] = $amenity->getId();
            }


            return $this->json($dto);
        } else {
            //throw new PostNotFoundException($id);
            return $this->json(["error" => "Post was not found by id:" . $id], 404);
        }
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

        $entity = new PropertyListing();
        $entity->setTitle($dto->title);
        $entity->setDescription($dto->description);

        foreach ($dto->amenities as $amenityId)
        {
            $amenity = $this->em->getRepository(PropertyAmenity::class)->findOneBy(['id' => $amenityId]);
            $entity->getAmenities()->add($amenity);
        }

        $this->em->persist($entity);
        $this->em->flush();

        return $this->json([], 201, ['Location' => '/listings/'.$entity->getId()]);
    }
}
