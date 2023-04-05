<?php

namespace App\DTO\Builder;

use App\DTO\DtoResourceInterface;
use App\Entity\EntityResourceInterface;

interface BuilderFactoryInterface
{
    public function createDtoBuilder(EntityResourceInterface $entity);

    public function createEntityBuilder(DtoResourceInterface $dto);
}