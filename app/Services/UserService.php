<?php

declare(strict_types=1);

namespace App\Services;

use App\App;
use App\DB;
use App\Entity\User;
use App\Exceptions\InvalidArgumentsException;

class UserService
{
    private DB $db;

    public function registerUser($name, $password, $email = null): void
    {
        $this->db = App::db();

        $user = new User(
            null,
            null,
            $name,
            $email,
            $password
        );

        $user->setPassword(password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]));

        try {
            $this->db->beginTransaction();

            $query = 'INSERT INTO users(displayed_name, username, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)';
            $stmt = $this->db->prepare($query);

            $stmt->bindValue(1, $user->getDisplayedName());
            $stmt->bindValue(2, $user->getUsername());
            $stmt->bindValue(3, $user->getEmail());
            $stmt->bindValue(4, $user->getPassword());
            $stmt->bindValue(5, $user->getCreatedAt()->format('Y-m-d H:i:s'));
            $stmt->bindValue(6, $user->getUpdatedAt()->format('Y-m-d H:i:s'));

            $stmt->execute();

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}