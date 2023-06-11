<?php

namespace App\PropertyListing\Query;
use App\PropertyListing\Entity\PropertyListing;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class ListingFilter
{
    private QueryBuilder $qb;

    public function __construct(
        private readonly Request $request,
        private readonly EntityManagerInterface $em
    )
    {
        $this->qb = $this->em->createQueryBuilder()
                ->select('pl')
                ->from(PropertyListing::class, 'pl');
    }

    public function executeQuery()
    {
        return $this->qb->getQuery()->execute();
    }

    private function ownerId($owner): void
    {
        $this->qb
            ->andWhere('pl.owner = :owner')
            ->setParameter('owner', $owner);
    }

    private function amenityId($ids): void
    {
        $this->qb
            ->join('pl.amenities', 'pla', Expr\Join::ON)
            ->andWhere($this->qb->expr()->in('pla.id', ':amenities'))
            ->groupBy('pl.id')
            ->having($this->qb->expr()->eq('COUNT(DISTINCT pla.id)', count($ids)))
            ->setParameter('amenities', $ids);
    }

    private function priceMin($value): void
    {
        $this->qb
            ->andWhere($this->qb->expr()->gt('pl.price', ':priceMin'))
            ->setParameter('priceMin', $value);
    }

    private function priceMax($value): void
    {
        $this->qb
            ->andWhere($this->qb->expr()->lt('pl.price', ':priceMax'))
            ->setParameter('priceMax', $value);
    }

    private function all(): array
    {
        return $this->request->query->all();
    }

    public function apply(): void
    {
        foreach ($this->all() as $name => $value) {
            $method = str_replace('_', '', lcfirst(ucwords($name, '_')));

            if (method_exists($this, $method) && is_callable([$this, $method])) {
                $this->$method($value);
            }
        }

    }
}