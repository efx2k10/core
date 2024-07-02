<?php

namespace APP\Models;

use Efx\Core\Dbal\Model;

class Post extends Model
{
    public function __construct(
        private ?int                $id,
        private string              $title,
        private string              $content,
        private ?\DateTimeImmutable $createdAt,
    )
    {
    }

    public static function create(
        string $title,
        string $content,
        ?int   $id = null,
        ?\DateTimeImmutable $createdAt = null
    ): static
    {

        return new static($id, $title, $content, $createdAt);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}