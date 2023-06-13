<?php

namespace App\PropertyListing\Query;
use App\PropertyListing\Entity\PropertyListing;
use App\University\Entity\University as UniversityEntity;
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
        //dd($this->qb->getQuery());
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
            ->addGroupBy('pl.id')
            ->andHaving($this->qb->expr()->eq('COUNT(DISTINCT pla.id)', count($ids)))
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

    private function placeId($value)
    {
        /** @var UniversityEntity $place */
        $place = $this->em->getRepository(UniversityEntity::class)->findOneBy(['id' => $value]);

        // build the query
        $this->qb
            ->addSelect('(2*6471*ASIN(SQRT(power(SIN((radians(pl.lat - :place_lat))/2), 2) + COS(radians(pl.lat))*COS(radians(:place_lat))*power(SIN((radians(pl.lon - :place_lon))/2), 2) ))) AS distance')
            ->andHaving('distance <= :radius')
            ->setParameter('place_lat', $place->getLat())
            ->setParameter('place_lon', $place->getLon())
            ->addOrderBy('distance');

        if (!array_key_exists('radius', $this->all())) $this->qb->setParameter('radius', 5);
    }

    private function radius($value)
    {
        if(!array_key_exists('place_id', $this->all())) return;

        $this->qb->setParameter('radius', $value);
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