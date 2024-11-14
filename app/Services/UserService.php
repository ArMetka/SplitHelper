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

    public function __construct()
    {
        $this->db = App::db();
    }

    public function registerUser(?string $name, ?string $password, $email = null): void
    {
        $user = new User(
            null,
            $name,
            $name,
            $email,
            $password
        );

        $query = 'SELECT count(*) FROM users WHERE username = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $user->getUsername());
        $stmt->execute();
        if ($stmt->fetch()['count'] !== 0) {
            throw new InvalidArgumentsException('User "' . $user->getUsername() . '" already exists!');
        }

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
            $id = $this->db->lastInsertId();

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        if ($id) {
            $_SESSION['user'] = (int)$id;
            $_SESSION['username'] = $user->getDisplayedName();
        }
    }

    public function loginUser(?string $username, ?string $password): void
    {
        $query = 'SELECT id, password, displayed_name FROM users WHERE username = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if (empty($user)) {
            throw new InvalidArgumentsException('Login or Password is incorrect!');
        }

        if (!password_verify($password, $user['password'])) {
            throw new InvalidArgumentsException('Login or Password is incorrect!');
        }

        $_SESSION['user'] = (int)$user['id'];
        $_SESSION['username'] = $user['displayed_name'];
    }

    public function findById(int $id): User
    {
        return new User(null, null, 'test1', null, 'test2');
    }

    public function updateDisplayedName(int $id, string $displayed_name): void
    {
        if (empty($displayed_name)) {
            throw new InvalidArgumentsException('Display name is an empty string');
        }

        if (strlen($displayed_name) > 40) {
            throw new InvalidArgumentsException('Display name exceeds 40 symbols');
        }

        $query = 'UPDATE users SET displayed_name = ? WHERE id = ?';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $displayed_name);
        $stmt->bindValue(2, $id);
        $stmt->execute();

        $_SESSION['username'] = $displayed_name;
    }
}