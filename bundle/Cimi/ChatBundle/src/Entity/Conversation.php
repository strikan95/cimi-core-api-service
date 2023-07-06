<?php

namespace Cimi\ChatBundle\Entity;

use Cimi\ChatBundle\Entity\Factory\ParticipantFactory;
use Cimi\ChatBundle\Repository\ConversationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use http\Message\Body;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['conversation_info'])]
    private ?int $id;

    #[ORM\Column]
    #[Groups(['conversation_info'])]
    private bool $isDM;

    #[Groups('conversation_with_participants')]
    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Participation::class, cascade: ['persist'])]
    private Collection $participants;

    #[Groups('conversation_with_messages')]
    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class, cascade: ['persist'])]
    private Collection $messages;

/*    #[Groups(['conversation_info'])]
    // private $last_message*/

    #[ORM\Column(name: "created_at", type: "datetime")]
    #[Groups(['conversation_info'])]
    private ?DateTime $createdAt = null;

    public function __construct(array $users, bool $isDM = false)
    {
        $this->isDM = $isDM;

        $this->participants = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->createdAt = new DateTime();

        $this->addUsers($users);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function isDM(): bool
    {
        return $this->isDM;
    }

    public function setIsDM(bool $isDM): void
    {
        $this->isDM = $isDM;
    }


    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function setMessages(array $messages): void
    {
        // Clear then set
        $this->messages = new ArrayCollection();
        foreach ($messages as $message)
        {
            $this->addMessage($message);
        }
    }

    public function addMessage(Message $message): void
    {
        $message->setConversation($this);
        $this->messages[] = $message;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function addUsers(array $users): void
    {
        foreach ($users as $user)
        {
            $this->addUser($user);
        }
    }

    public function addUser(ChatUserInterface $user): void
    {
        $this->participants[] = ParticipantFactory::buildParticipant($user, $this);
    }
}