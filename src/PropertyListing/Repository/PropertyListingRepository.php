<?php
namespace App\PropertyListing\Repository;


use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PropertyListingEntity>
 *
 * @method PropertyListingEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyListingEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyListingEntity[]    findAll()
 * @method PropertyListingEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyListingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropertyListingEntity::class);
    }

    public function save(PropertyListingEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PropertyListingEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findById($id): ?PropertyListingEntity
    {
        return $this->findOneBy(['id' => $id]);
    }

//    /**
//     * @return Property[] Returns an array of Property objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Property
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
