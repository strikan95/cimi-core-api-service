<?php

namespace App\University\Filter;

use App\University\Entity\University as UniversityEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class UniversityFilter
{
    private QueryBuilder $qb;

    public function __construct(
        private readonly Request $request,
        private readonly EntityManagerInterface $em
    )
    {
        $this->qb = $this->em->createQueryBuilder()
            ->select('uni')
            ->from(UniversityEntity::class, 'uni');
    }

    public function executeQuery()
    {
        return $this->qb->getQuery()->execute();
    }

    private function search($str): void
    {
        $this->qb
            ->andWhere($this->qb->expr()->like('uni.name', $this->qb->expr()->literal($str . '%')))
            ->orWhere($this->qb->expr()->like('uni.name', $this->qb->expr()->literal('% ' . $str . '%')));
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