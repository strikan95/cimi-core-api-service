<?php

namespace Cimi\ChatBundle\Entity;

use Cimi\ChatBundle\Repository\ParticipantRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[OneToOne(targetEntity: ChatUserInterface::class)]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?ChatUserInterface $user;

    #[ORM\ManyToMany(targetEntity: Conversation::class, inversedBy: 'participants')]
    #[ORM\JoinTable(name: 'participants_conversations')]
    private ?Collection $conversations;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Message::class)]
    private ?Collection $messages;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUser(): ?ChatUserInterface
    {
        return $this->user;
    }

    public function setUser(?ChatUserInterface $user): void
    {
        $this->user = $user;
    }

    public function getConversations(): ?Collection
    {
        return $this->conversations;
    }

    public function setConversations(?Collection $conversations): void
    {
        $this->conversations = $conversations;
    }

    public function addConversation(Conversation $conversation): void
    {
        $this->conversations[] = $conversation;
    }

    public function getMessages(): ?Collection
    {
        return $this->messages;
    }

    public function setMessages(?Collection $messages): void
    {
        $this->messages = $messages;
    }

    public function addMessage(Message $message): void
    {
        $this->messages[] = $message;
    }
}