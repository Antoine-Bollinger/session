<?php
namespace ABollinger;

/**
 * Class Session
 *
 * The Session class manages user session-related functionalities.
 * It provides methods for session initiation, user authentication, login, and logout.
 */
final class Session
{
    /**
     * Constructor for the Session class.
     *
     * Initializes the session if it is not already active.
     */
    public function __construct(

    ) {
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
        if (!isset($_SESSION["userId"]) || !isset($_SESSION["token"])) return false;
        if ($isSameServer) return true;
        $headers = array_change_key_case(getallheaders());
        if (!array_key_exists("authorization", $headers)) return false;
        if (!substr($headers["authorization"], 0, 7) === "Bearer ") return false;
        if ($headers['authorization'] !== $_SESSION["token"]) return false;
        return true;
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
    }

    /**
     * Logs out the user by unsetting session variables and destroying the session.
     *
     * @return void
     */
    public function logout(

    ) :void {
        unset($_SESSION["userId"]);
        unset($_SESSION["token"]);
        session_destroy();
    }
}  
