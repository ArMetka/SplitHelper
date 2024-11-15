<?php

declare(strict_types=1);

namespace App\Entity;

use App\Exceptions\InvalidArgumentsException;

class User
{
    public function __construct(
        private ?int $id,
        private ?string $displayedName,
        private string $username,
        private ?string $email,
        private string $password,
        private \DateTime $createdAt = new \DateTime(),
        private \DateTime $updatedAt = new \DateTime()
    ) {
        if (empty($username)) {
            throw new InvalidArgumentsException('Username is an empty string');
        }
        if (strlen($username) > 40) {
            throw new InvalidArgumentsException('Username exceeds 40 symbols');
        }
        if (empty($password)) {
            throw new InvalidArgumentsException('Password is an empty string');
        }
        if (strlen($password) < 8) {
            throw new InvalidArgumentsException('Password must be at least 8 symbols long');
        }
        if (isset($email) && (strlen($email) > 64)) {
            throw new InvalidArgumentsException('Email exceeds 64 symbols');
        }
    }

    public function getDisplayedName(): ?string
    {
        return $this->displayedName;
    }

    public function setDisplayedName(?string $displayedName): void
    {
        $this->displayedName = $displayedName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}