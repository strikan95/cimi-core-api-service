<?php

namespace Cimi\ChatBundle\Entity;

use Cimi\ChatBundle\Repository\ConversationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Participation::class, cascade: ['persist'])]
    private Collection $participants;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class, cascade: ['persist'])]
    private Collection $messages;

    // private $last_message

    #[ORM\Column(name: "created_at", type: "datetime")]
    private ?DateTime $createdAt = null;

    public function __construct(array $participants, Message $initialMessage = null)
    {
        $this->participants = new ArrayCollection();
        $this->messages = new ArrayCollection();


        $this->setParticipants($participants);
        $this->addMessage($initialMessage);

        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function setParticipants(array $participants): void
    {
        // Clear then set
        $this->participants = new ArrayCollection();
        foreach ($participants as $participant)
        {
            $this->addParticipant($participant);
        }
    }

    public function addParticipant(Participation $participation): void
    {
        $participation->setConversation($this);
        $this->participants[] = $participation;
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
}