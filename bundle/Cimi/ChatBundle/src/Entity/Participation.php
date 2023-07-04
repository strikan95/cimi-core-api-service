<?php

namespace Cimi\ChatBundle\Entity;

use Cimi\ChatBundle\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: ChatUserInterface::class)]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?ChatUserInterface $user;

    #[ORM\ManyToOne(targetEntity: Conversation::class, inversedBy: 'participants')]
    private Conversation $conversation;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Message::class, cascade: ['persist'])]
    private ?Collection $messages;

    public function __construct(ChatUserInterface $user)
    {
        $this->user = $user;
    }

    /*    public function __construct(ChatUserInterface $user, Conversation $conversation)
        {
            $this->user = $user;
            $this->conversation = $conversation;
        }*/

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

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): void
    {
        $this->conversation = $conversation;
    }

    public function getMessages(): ?Collection
    {
        return $this->messages;
    }

    public function setMessages(Collection $messages): void
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
        $message->setSender($this);
        $this->messages[] = $message;
    }
}