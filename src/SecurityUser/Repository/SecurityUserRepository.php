<?php

namespace App\SecurityUser\Repository;

use App\SecurityUser\Entity\SecurityUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SecurityUser>
 *
 * @method SecurityUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecurityUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecurityUser[]    findAll()
 * @method SecurityUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecurityUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SecurityUser::class);
    }

    public function save(SecurityUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SecurityUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByIdentifier(string $identifier): ?SecurityUser
    {
        return $this->findOneBy(['auth0_id' => $identifier]);
    }

//    /**
//     * @return ApiUser[] Returns an array of ApiUser objects
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

//    public function findOneBySomeField($value): ?ApiUser
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
