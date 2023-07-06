<?php

namespace Cimi\ChatBundle\Repository;

use Cimi\ChatBundle\Entity\ChatUserInterface;
use Cimi\ChatBundle\Entity\Conversation;
use Cimi\ChatBundle\Entity\Participation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Participation>
 *
 * @method Participation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participation[]    findAll()
 * @method Participation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participation::class);
    }

    public function save(Participation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Participation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findIfIsParticipant(Conversation $conversation, ChatUserInterface $chatUser): ?Participation
    {
        return $this->findOneBy(
            [
                'user' => $chatUser->getId(),
                'conversation' => $conversation->GetId()
            ]
        );
    }

    public function getAllParticipations(ChatUserInterface $user): array
    {
        return $this->findBy(
            ['user' => $user->getId()]
        );
    }
}