<?php
namespace Abollinger;

/**
 * Class Session
 *
 * The Session class manages user session-related functionalities.
 * It provides methods for session initiation, user authentication, login, and logout.
 */
final class Session
{
    /**
     * @var \SQLite3 $db The SQLite database connection object used for interacting with the database.
     */
    private $db;

    /**
     * Constructor for the Session class.
     *
     * Initializes the session if it is not already active.
     */
    public function __construct(

    ) {
        $this->db = new SQLite();
        if (session_status() !== PHP_SESSION_ACTIVE) 
            session_start();
    }

    /**
     * Checks if the user is logged in and authorized.
     *
     * @param bool $isSameServer Indicates if the request is from the same server (default: false)
     * @return bool Returns true if the user is logged in and authorized; otherwise, false.
     */
    public function isLoggedAndAuthorized(
        $isSameServer = false
    ) :bool {
        try {
            if ($isSameServer) {
                if (!isset($_SESSION["userId"]) || !isset($_SESSION["token"])) {
                    throw new \Exception("No userId or token in the session were found.");
                }
            } else {
                $headers = array_change_key_case(getallheaders());
                $id = $headers["x-client-id"] ?? null;
                $token = $this->db->getTokenFromDatabase($id);
                $authorization = $headers["authorization"] ?? $_SERVER["HTTP_AUTHORIZATION"] ?? $_SERVER["REDIRECT_REDIRECT_HTTP_AUTHORIZATION"] ?? null; 
                if (!$authorization || !$id) {
                    throw new \Exception("No authorization or x-client-id found in the header.");
                }
                if (!substr($authorization, 0, 7) === "Bearer ") {
                    throw new \Exception(sprintf("Authorization is present but no Bearer token found.\nAuthorization looks like: %s.", $authorization));
                }
                if ($authorization !== $token) {
                    throw new \Exception(sprintf("Bearer token doesn't match.\nHeader's token: %s\nSession's token: %s", $authorization, $token));
                }
            }
            return true;
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Logs in the user by setting session variables.
     *
     * @param array $arr An array containing user-specific data (userId, token)
     * @return void
     */
    public function login(
        $arr
    ) :void {
        $_SESSION["userId"] = $arr["userId"];
        $_SESSION["token"] = $arr["token"];
        $this->db->saveTokenToDatabase(
            $arr["userId"],
            $arr["token"]
        );
    }

    /**
     * Logs out the user by unsetting session variables and destroying the session.
     *
     * @param array $arr An array containing user-specific data (userId)
     * @return void
     */
    public function logout(
        $arr
    ) :void {
        unset($_SESSION["userId"]);
        unset($_SESSION["token"]);
        $this->db->deleteTokenFromDatabase(
            $arr["userId"]
        );
        session_destroy();
    }
}  
