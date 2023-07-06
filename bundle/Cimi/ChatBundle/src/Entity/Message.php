<?php

namespace Cimi\ChatBundle\Entity;

use Cimi\ChatBundle\Repository\MessageRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('conversation_with_messages')]
    private ?int $id;

    #[ORM\Column]
    #[Groups('conversation_with_messages')]
    private ?string $body;

    #[ORM\ManyToOne(targetEntity: Participation::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(name: 'sender_id', referencedColumnName: 'id')]
    #[Groups('conversation_with_messages')]
    private Participation $sender;

    #[ORM\ManyToOne(targetEntity: Conversation::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(name: 'conversation_id', referencedColumnName: 'id')]
    private ?Conversation $conversation;

    #[ORM\Column(name: "created_at", type: "datetime")]
    private ?DateTime $createdAt;

    public function __construct(Conversation $conversation, Participation $participant, string $body)
    {
        $this->conversation = $conversation;
        $this->sender = $participant;
        $this->body = $body;

        $this->createdAt = new DateTime();
    }

    /*    public function __construct(string $body, Participation $sender, Conversation $conversation = null)
        {
            $this->body = $body;
            $this->sender = $sender;
            $this->conversation = $conversation;
            $this->createdAt = new DateTime();
        }*/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): void
    {
        $this->body = $body;
    }

    public function getSender(): Participation
    {
        return $this->sender;
    }

    public function setSender(Participation $sender): void
    {
        $this->sender = $sender;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): void
    {
        $this->conversation = $conversation;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}