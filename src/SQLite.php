<?php 
/*
 * This file is part of the Abollinger\Session package.
 *
 * (c) Antoine Bollinger <abollinger@partez.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Abollinger;

/**
 * Class SQLite
 *
 * This class provides methods to interact with an SQLite database
 * for storing and retrieving tokens associated with IDs.
 */
final class SQLite
{
    /** @var string $path THe path to the SQLite database file. */
    private string $path;

    /** @var \SQLite3 $db The SQLite database connection object. */
    private \SQLite3 $db;

    /**
     * SQLite constructor.
     *
     * @param string $path Path to the SQLite database file.
     */
    public function __construct(
        string $path = __DIR__ . "/token.db"
    ) {
        $this->path = $path;
        $this->init();
    }

    /**
     * Initializes the SQLite database and creates the tokens table if it does not exist.
     */
    private function init(

    ) :void {
        $this->db = new \SQLite3($this->path);
        $this->db->exec("CREATE TABLE IF NOT EXISTS tokens (id INTEGER PRIMARY KEY, token TEXT)");
    }

    /**
     * Saves a token associated with an ID to the database.
     *
     * @param int    $id    The ID associated with the token.
     * @param string $token The token to be saved.
     */
    public function saveTokenToDatabase(
        string $id,
        string $token
    ) :void {
        $statement = $this->db->prepare("SELECT * FROM tokens WHERE id = :id");
        $statement->bindParam(":id", $id, SQLITE3_INTEGER);
        $result = $statement->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);
        if ($user) {
            $statement = $this->db->prepare("UPDATE tokens SET token = :token WHERE id = :id");
        } else {
            $statement = $this->db->prepare("INSERT INTO tokens (id, token) VALUES (:id, :token)");
        }
        $statement->bindParam(":id", $id, SQLITE3_INTEGER);
        $statement->bindParam(":token", $token, SQLITE3_TEXT);
        $statement->execute();
    }

    /**
     * Retrieves the token associated with the given ID from the database.
     *
     * @param int $id The ID for which to retrieve the token.
     *
     * @return string|null The token associated with the ID, or null if not found.
     */
    public function getTokenFromDatabase(
        int $id
    ) :string|null {
        $statement = $this->db->prepare("SELECT token FROM tokens WHERE id = :id");
        $statement->bindParam(":id", $id, SQLITE3_INTEGER);
        $result = $statement->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row ? $row['token'] : null;
    }

    /**
     * Deletes the token associated with the given ID from the database.
     *
     * @param int $id The ID for which to delete the token.
     */
    public function deleteTokenFromDatabase(
        int $id
    ) :void {
        $statement = $this->db->prepare("DELETE FROM tokens WHERE id = :id");
        $statement->bindParam(":id", $id, SQLITE3_INTEGER);
        $statement->execute();
    }
}
