<?php
namespace Abollinger;

final class SQLite
{
    private $path;
    private $db;

    public function __construct(
        $path = __DIR__ . "/token.db"
    ) {
        $this->path = $path;
        $this->init();

    }

    private function init(

    ) {
        $this->db = new \SQLite3($this->path);
        $this->db->exec("CREATE TABLE IF NOT EXISTS tokens (id INTEGER PRIMARY KEY, token TEXT)");
    }

    public function saveTokenToDatabase(
        $id,
        $token
    ) {
        $statement = $this->db->prepare("INSERT INTO tokens (id, token) VALUES (:id, :token)");
        $statement->bindParam(":id", $id, SQLITE3_INTEGER);
        $statement->bindParam(":token", $token, SQLITE3_TEXT);
        $statement->execute();
    }

    public function getTokenFromDatabase(
        $id
    ) {
        $statement = $this->db->prepare("SELECT token FROM tokens WHERE id = :id");
        $statement->bindParam(":id", $id, SQLITE3_INTEGER);
        $result = $statement->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row ? $row['token'] : null;
    }

    public function deleteTokenFromDatabase(
        $id
    ) {
        $statement = $this->db->prepare("DELETE FROM tokens WHERE id = :id");
        $statement->bindParam(":id", $id, SQLITE3_INTEGER);
        $statement->execute();
    }
}