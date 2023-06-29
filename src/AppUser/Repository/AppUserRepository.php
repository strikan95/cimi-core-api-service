<?php

namespace App\AppUser\Repository;

use App\AppUser\Entity\AppUser as AppUserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppUserEntity>
 *
 * @method AppUserEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppUserEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppUserEntity[]    findAll()
 * @method AppUserEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppUserEntity::class);
    }

    public function save(AppUserEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AppUserEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByAuthIdentifier($identifier): ?AppUserEntity
    {
        return $this->findOneBy(['auth0Identifier' => $identifier]);
    }

    public function findByLocalId($id): ?AppUserEntity
    {
        return $this->findOneBy(['id' => $id]);
    }

//    /**
//     * @return AppUser[] Returns an array of AppUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AppUser
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
