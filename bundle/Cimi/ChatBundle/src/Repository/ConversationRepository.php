<?php

namespace Cimi\ChatBundle\Repository;

use Cimi\ChatBundle\Entity\ChatUserInterface;
use Cimi\ChatBundle\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Conversation>
 *
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function save(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findById(int $id): ?Conversation
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function getAllUsersConversations(int $id)
    {
        $query = $this->getEntityManager()->createQueryBuilder('c')
            ->select('c')
            ->from(Conversation::class, 'c')
            ->innerJoin('c.participants', 'p')
            ->andWhere('p.user = :userId')
            ->setParameter('userId', $id)
            ->getQuery();

        return $query->getResult();
    }
}