<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use App\Validator\ThreadMessageValid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Index(name: "messages_sender_id_idx", columns: ["sender_id"])]
#[ORM\Index(name: "messages_recipient_id_idx", columns: ["recipient_id"])]
#[ORM\Index(name: "messages_timestamp_idx", columns: ["timestamp"])]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Positive]
    #[Assert\NotIdenticalTo(
        propertyPath: 'recipientId',
    )]
    private ?int $senderId = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Positive]
    private ?int $recipientId = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $content = null;

    #[ORM\Column]
    private int $level = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    #[ORM\ManyToOne(targetEntity: Message::class, inversedBy: 'threadMessages')]
    #[ORM\JoinColumn(name: 'thread_msg_id', referencedColumnName: 'id')]
    #[ThreadMessageValid]
    private Message|null $threadMessage = null;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'threadMessage', cascade: ['persist'])]
    private Collection $threadMessages;

    public function __construct() {
        $this->timestamp = new \DateTime();
        $this->threadMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSenderId(): ?int
    {
        return $this->senderId;
    }

    public function setSenderId(int $senderId): static
    {
        $this->senderId = $senderId;

        return $this;
    }

    public function getRecipientId(): ?int
    {
        return $this->recipientId;
    }

    public function setRecipientId(int $recipientId): static
    {
        $this->recipientId = $recipientId;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * @return Message|null
     */
    public function getThreadMessage(): ?Message
    {
        return $this->threadMessage;
    }

    /**
     * @param Message|null $threadMessage
     */
    public function setThreadMessage(?Message $threadMessage): void
    {
        $this->threadMessage = $threadMessage;
        $this->setLevel($threadMessage->getLevel() + 1);
    }

    public function addThreadMessage(Message $message): void
    {
        $message->setThreadMessage($this);
        $this->threadMessages->add($message);
    }

    /**
     * @return Collection
     */
    public function getThreadMessages(): Collection
    {
        return $this->threadMessages;
    }
}
