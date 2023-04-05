<?php

namespace App\Controller;

use App\DTO\Builder\BuilderFactoryInterface;
use App\DTO\DtoResourceInterface;
use App\DTO\Response\Property\CreatePropertyDto;
use App\Entity\EntityResourceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Http\Discovery\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    protected function resolveGetAction(Request $request, int $id): JsonResponse|\Exception
    {
        $entity = $this->em->getRepository($this->getEntityClassName())->findOneBy(['id' => $id]);

        if(null === $entity)
        {
            throw new NotFoundException('Resource with id' . $id . ' not found');
        }

        $dto = $this->builder->createDtoBuilder($entity)->buildDto();

        return $this->json($dto);
    }

    protected function resolveCreateAction(Request $request): JsonResponse
    {
        $dto = $this->resolveDto($request);
        $entity = $this->builder->createEntityBuilder($dto)->buildEntity();

        $this->em->persist($entity);
        $this->em->flush();

        return $this->json([], 201, ['Location' => $request->getRequestUri().'/'.$entity->getId()]);
    }

    protected function resolveUpdateAction(Request $request, int $id): JsonResponse
    {
        $dto = $this->resolveDto($request);
        $entity = $this->builder->createEntityBuilder($dto)->buildEntity($id);

        $this->em->persist($entity);
        $this->em->flush();

        return $this->json([], Response::HTTP_OK);
    }

    protected function resolveDeleteAction(Request $request, int $id): JsonResponse
    {
        $entity = $this->em->getRepository($this->getEntityClassName())->findOneBy(['id' => $id]);

        if(null === $entity)
        {
            throw new NotFoundException('Resource with id' . $id . ' not found');
        }

        $this->em->remove($entity);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    protected function resolveDto(Request $request)
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

        return $dto;
    }

    protected abstract function getInputDtoClassName(): string;
    protected abstract function getEntityClassName(): string;
}