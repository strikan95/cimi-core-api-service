<?php

namespace App\Controller;

use App\DTO\Builder\BuilderFactoryInterface;
use App\DTO\DtoResourceInterface;
use App\DTO\Response\Property\CreatePropertyDto;
use App\Entity\EntityResourceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractApiController extends AbstractController
{
    public function __construct
    (
        protected readonly SerializerInterface $serializer,
        protected readonly ValidatorInterface $validator,
        protected readonly EntityManagerInterface $em,
        protected readonly BuilderFactoryInterface $builder
    )
    {
    }

    protected function resolveGetAction(Request $request, int $id): JsonResponse
    {
        $entity = $this->em->getRepository($this->getEntityClassName())->findOneBy(['id' => $id]);

        if(null === $entity)
        {
            $this->createNotFoundException('Resource with id' .$id. 'not found');
        }

        $dto = $this->builder->createDtoBuilder($entity)->buildDto();

        return $this->json($dto);
    }

    protected function resolveCreateAction(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $dto = $this->serializer->deserialize
        (
            $content,
            $this->getInputDtoClassName(),
            'json'
        );

        //Validate
        $errors = $this->validator->validate($dto);
        if(count($errors) > 0)
        {
            throw new BadRequestException('Validation failed');
        }

        $entity = $this->builder->createEntityBuilder($dto)->buildEntity();

        $this->em->persist($entity);
        $this->em->flush();

        return $this->json([], 201, ['Location' => '/listings/'.$entity->getId()]);
    }


    protected abstract function getInputDtoClassName(): string;
    protected abstract function getEntityClassName(): string;
}