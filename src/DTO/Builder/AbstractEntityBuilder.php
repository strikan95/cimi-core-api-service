<?php

namespace App\DTO\Builder;

use App\DTO\DtoResourceInterface;
use App\Entity\EntityResourceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Http\Discovery\Exception\NotFoundException;

abstract class AbstractEntityBuilder
{
    public function __construct
    (
        protected readonly EntityManagerInterface $em,
        protected readonly DtoResourceInterface $dto
    )
    {
    }

    public function buildEntity(?int $id = null): EntityResourceInterface
    {
        if(null === $id)
        {
            return $this->buildNewEntity();
        }

        return $this->buildUpdatedEntity($id);
    }

    private function buildNewEntity(): EntityResourceInterface
    {
        return $this->build(new ($this->getEntityClassName())());
    }

    private function buildUpdatedEntity(int $id): EntityResourceInterface
    {
        $entity = $this->em
            ->getRepository($this->getEntityClassName())
            ->findOneBy(['id' => $id]);

        if(null === $entity)
        {
            throw new NotFoundException('Entity not found');
        }

        return $this->build($entity);
    }

    protected abstract function build(EntityResourceInterface $entity): EntityResourceInterface;
    protected abstract function getEntityClassName(): string;
}