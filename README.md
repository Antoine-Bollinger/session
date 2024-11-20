# Abollinger\Session

**Abollinger\Session** is a PHP library designed to manage user session-related functionalities. It offers an easy-to-use interface for session management, user authentication, login, and logout processes, leveraging PHP's native session handling mechanisms and SQLite for token storage.

## Features

- **Session Management**: Automatically initializes a session if not already active.

- **User Authentication**: Supports authentication checks with token validation, including cross-server requests.

- **Login and Logout**: Provides methods to securely log in and log out users, managing session variables and database records.

- **Error Handling**: Logs errors for debugging and ensures secure exception handling.

## Requirements

- PHP 7.4 or higher
- SQLite3 extension enabled

## Installation

Clone or download this repository and include the `Session` class in your project:

```php
require_once 'path/to/Session.php';
use Abollinger\Session;
```

## Usage

### Initialization

```php
use Abollinger\Session;

// Instantiate the session manager
$session = new Session();
```

### Login

```php
$session->login([
    "userId" => "exampleUser123",
    "token"  => "secureToken123"
]);
```

### Authentication Check

```php
$isAuthenticated = $session->isLoggedAndAuthorized($isSameServer = true);

if ($isAuthenticated) {
    echo "User is authenticated!";
} else {
    echo "Authentication failed.";
}
```

### Logout

```php
$session->logout([
    "userId" => "exampleUser123"
]);
```

## API Reference

### Constructor

`__construct()`
Initializes a session if none exists and sets up the SQLite connection.

### Methods

`isLoggedAndAuthorized(bool $isSameServer = false): bool`

Description: Checks if a user is logged in and authorized.

- Parameters:

    - `$isSameServer (bool)`: Defaults to false. Determines whether to validate via session variables or headers.

    - Returns: `true` if the user is authenticated; otherwise `false`.


`login(array $arr): void`

Description: Logs in a user by setting session variables and saving the token to the database.

- Parameters:
    - `$arr` (array): Contains `userId` and `token`.


`logout(array $arr): void`

Description: Logs out a user by removing session variables, deleting the token from the database, and destroying the session.

- Parameters:
    - `$arr` (array): Contains `userId`.

## Example Workflow

1. Start a session:

```php
$session = new Session();
```

2. Log in a user:

```php
$session->login([
    "userId" => "exampleUser",
    "token"  => "exampleToken"
]);

```

3. Verify user authentication:

```php
if ($session->isLoggedAndAuthorized()) {
    echo "User is authenticated!";
} else {
    echo "Authentication failed.";
}
```

4. Log out the user:

```php
$session->logout([
    "userId" => "exampleUser"
]);
```

## Licence 

This library is licensed under the MIT License. For full license details, see the `LICENCE` file distributed with this source code.

## Author

Antoine Bollinger
Email: abollinger@partez.net

For contributions, issues, or feedback, feel free to contact the author or open a GitHub issue.