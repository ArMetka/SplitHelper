<?php

declare(strict_types=1);

namespace App\Services;

use App\App;
use App\DB;
use App\Entity\Split;
use App\Enums\SplitAccessLevel;

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

    public function fetchAllById(string $id): array
    {
        $query = <<<TEXT
SELECT splits.id, title, is_public, users.displayed_name, splits.created_at, splits.updated_at FROM splits
INNER JOIN users on users.id = splits.owner_id
WHERE splits.id = '{$id}'
TEXT;
        $stmt = $this->db->query($query);
        $splitData = $stmt->fetch();

        $query = <<<TEXT
SELECT * FROM generated_tables.items_{$id}
ORDER BY id
TEXT;
        $stmt = $this->db->query($query);
        $itemsData = $stmt->fetchAll();

        $query = <<<TEXT
SELECT user_id, users.displayed_name, array_to_json(item_ids) AS item_ids FROM generated_tables.clients_{$id}
INNER JOIN users ON users.id = generated_tables.clients_{$id}.user_id
ORDER BY user_id
TEXT;
        $stmt = $this->db->query($query);
        $clientsData = $stmt->fetchAll();
        foreach ($clientsData as &$clientsDatum) {
            $clientsDatum['item_ids'] = json_decode($clientsDatum['item_ids']);
        }

        return [
            'split' => $splitData,
            'items' => $itemsData,
            'clients' => $clientsData
        ];
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
        $stmt->bindValue(3, $split->isPublic() ? 'true' : 'false');
        $stmt->bindValue(4, $split->getOwnerId());
        $stmt->bindValue(5, '{' . implode(",", $split->getViewerIds()) . '}');
        $stmt->bindValue(6, '{' . implode(",", $split->getEditorIds()) . '}');
        $stmt->bindValue(7, $split->getItemsTableName());
        $stmt->bindValue(8, $split->getCreatedAt()->format('Y-m-d H:i:s'));
        $stmt->bindValue(9, $split->getUpdatedAt()->format('Y-m-d H:i:s'));

        $stmt->execute();
    }

    public function checkSplitAccess(string $splitId, int $userId, SplitAccessLevel $requestedAccess): bool
    {
        if ($requestedAccess === SplitAccessLevel::Owner) {
            $query = 'SELECT owner_id FROM splits WHERE id = ?';
            $stmt = $this->db->prepare($query);

            $stmt->bindValue(1, $splitId);
            $stmt->execute();

            $ownerId = $stmt->fetch()['owner_id'] ?? null;

            return ((isset($ownerId)) && ($ownerId === $userId));
        } else {
            if ($requestedAccess === SplitAccessLevel::Editor) {
                $query = 'SELECT owner_id FROM splits WHERE (id = ?) AND ((? = ANY(editor_ids)) OR (? = owner_id))';
                $stmt = $this->db->prepare($query);

                $stmt->bindValue(1, $splitId);
                $stmt->bindValue(2, $userId);
                $stmt->bindValue(3, $userId);
                $stmt->execute();

                return ($stmt->rowCount() !== 0);
            } else {
                if ($requestedAccess === SplitAccessLevel::Viewer) {
                    $query = 'SELECT owner_id FROM splits WHERE (id = ?) AND ((? = ANY(editor_ids)) OR (? = ANY(viewer_ids)) OR (? = owner_id) OR (is_public = true))';
                    $stmt = $this->db->prepare($query);

                    $stmt->bindValue(1, $splitId);
                    $stmt->bindValue(2, $userId);
                    $stmt->bindValue(3, $userId);
                    $stmt->bindValue(4, $userId);
                    $stmt->execute();

                    return ($stmt->rowCount() !== 0);
                } else {
                    return false;
                }
            }
        }
    }
}