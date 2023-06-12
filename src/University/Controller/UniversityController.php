<?php

namespace App\University\Controller;

use App\University\Dto\University as UniversityDto;
use App\University\Filter\UniversityFilter;
use App\University\UniversityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class UniversityController extends AbstractController
{
    public function __construct
    (
        private readonly UniversityService $universityService,
        private readonly EntityManagerInterface $entityManager
    ){
    }

    #[Route('/api/v1/universities/index', name: 'api.universities.index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $filter = new UniversityFilter($request, $this->entityManager);
        $filter->apply();
        $results = $filter->executeQuery();

        $dtos = [];
        foreach ($results as $result)
        {
            $dto = new UniversityDto($result);
            $dtos[] = $dto;
        }

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['universities_basic'])
            ->toArray();

        return $this->json($dtos, Response::HTTP_OK, context:$context);
    }

    #[Route('/api/v1/universities/{id}', name: 'api.universities.get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $entity = $this->universityService->getById($id);

        $dto = new UniversityDto($entity);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['universities_extended'])
            ->toArray();

        return $this->json($dto, Response::HTTP_OK, context:$context);
    }
}