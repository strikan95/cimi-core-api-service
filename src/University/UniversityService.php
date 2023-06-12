<?php

namespace App\University;

use App\University\Entity\University as UniversityEntity;
use App\University\Repository\UniversityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UniversityService
{
    public function __construct
    (
        private readonly UniversityRepository $universityRepository
    ){
    }

    public function getById($id): UniversityEntity
    {
        $entity = $this->universityRepository->find($id);

        if(null === $entity)
        {
            throw new NotFoundHttpException("University with id ". $id ." couldn't be found.");
        }

        return $entity;
    }
}