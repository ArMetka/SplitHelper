<?php

declare(strict_types=1);

namespace App\Services;

use App\App;
use App\DB;
use App\Entity\Split;

class SplitService
{
    private DB $db;

    public function __construct()
    {
        $this->db = App::db();
    }

    public function createSplit(
        string $title,
        bool $isPublic,
        int $ownerId = -1,
        array $viewIds = [],
        array $editorIds = [],
    ): string {
        $ownerId = $_SESSION['user'];
        try {
            $this->db->beginTransaction();

            $uniqueStr = $this->generateUniqueStr();
            $itemsTableName = 'items_' . $uniqueStr;
            $clientsTableName = 'clients_' . $uniqueStr;

            $split = new Split(
                $uniqueStr,
                $title,
                $isPublic,
                $ownerId,
                $viewIds,
                $editorIds,
                $itemsTableName
            );

            $this->createItemsTable($itemsTableName);
            $this->createClientsTable($clientsTableName);
            $this->insertIntoSplits($split);

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        return $uniqueStr;
    }

    public function findByOwnerId(int $ownerId): array
    {
        $query = <<<TEXT
SELECT splits.id, splits.title, users.displayed_name, splits.updated_at
FROM splits
INNER JOIN users ON splits.owner_id = users.id
WHERE splits.owner_id = {$ownerId}
TEXT;
        $stmt = $this->db->query($query);
        $splits = $stmt->fetchAll();
        if ($splits === false) {
            return [];
        }
        return $splits;
    }

    public function findAllPublic(): array
    {
        $query = <<<TEXT
SELECT splits.id, splits.title, users.displayed_name, splits.updated_at
FROM splits
INNER JOIN users ON splits.owner_id = users.id
WHERE splits.is_public = true
TEXT;
        $stmt = $this->db->query($query);
        $splits = $stmt->fetchAll();
        if ($splits === false) {
            return [];
        }
        return $splits;
    }

    private function generateUniqueStr(): string
    {
        $str = substr(md5(random_bytes(5)), -11);

        $query = 'SELECT * FROM splits WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $str);
        $stmt->execute();

        while ($stmt->rowCount() !== 0) {
            $str = substr(md5(random_bytes(5)), -11);
            $stmt->execute();
        }

        return $str;
    }

    private function createItemsTable(string $tableName): void
    {
        $query = <<<TEXT
CREATE TABLE generated_tables.{$tableName} (
    id SERIAL PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    base_price REAL NOT NULL,
    price_modifier REAL NOT NULL,
    modified_price REAL NOT NULL
);
TEXT;
        $this->db->query($query);
    }

    private function createClientsTable(string $tableName): void
    {
        $query = <<<TEXT
CREATE TABLE generated_tables.{$tableName} (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    item_ids INT[] NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id)
);
TEXT;
        $this->db->query($query);
    }

    private function insertIntoSplits(Split $split): void
    {
        $query = <<<TEXT
INSERT INTO splits (id, title, is_public, owner_id, viewer_ids, editor_ids, items_table_name, created_at, updated_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);
TEXT;
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(1, $split->getId());
        $stmt->bindValue(2, $split->getTitle());
        $stmt->bindValue(3, $split->isPublic());
        $stmt->bindValue(4, $split->getOwnerId());
        $stmt->bindValue(5, '{' . implode(",", $split->getViewerIds()) . '}');
        $stmt->bindValue(6, '{' . implode(",", $split->getEditorIds()) . '}');
        $stmt->bindValue(7, $split->getItemsTableName());
        $stmt->bindValue(8, $split->getCreatedAt()->format('Y-m-d H:i:s'));
        $stmt->bindValue(9, $split->getUpdatedAt()->format('Y-m-d H:i:s'));

        $stmt->execute();
    }
}