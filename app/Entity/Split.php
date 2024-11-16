<?php

declare(strict_types=1);

namespace App\Entity;

use App\Exceptions\InvalidArgumentsException;

class Split
{
    public function __construct(
        private string $id,
        private string $title,
        private bool $isPublic,
        private int $ownerId,
        private array $viewerIds,
        private array $editorIds,
        private string $itemsTableName,
        private \DateTime $createdAt = new \DateTime(),
        private \DateTime $updatedAt = new \DateTime()
    ) {
        if (strlen($title) > 40) {
            throw new InvalidArgumentsException('Split\'s title exceeds 40 symbols');
        }
        if (strlen($title) < 3) {
            throw new InvalidArgumentsException('Split\'s title is less than 3 symbols');
        }

    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getItemsTableName(): string
    {
        return $this->itemsTableName;
    }

    public function setItemsTableName(string $itemsTableName): void
    {
        $this->itemsTableName = $itemsTableName;
    }

    public function getEditorIds(): array
    {
        return $this->editorIds;
    }

    public function setEditorIds(array $editorIds): void
    {
        $this->editorIds = $editorIds;
    }

    public function getViewerIds(): array
    {
        return $this->viewerIds;
    }

    public function setViewerIds(array $viewerIds): void
    {
        $this->viewerIds = $viewerIds;
    }

    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    public function setOwnerId(int $ownerId): void
    {
        $this->ownerId = $ownerId;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): void
    {
        $this->isPublic = $isPublic;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }
}