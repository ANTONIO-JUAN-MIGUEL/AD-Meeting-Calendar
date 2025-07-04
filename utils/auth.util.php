<?php
declare(strict_types=1);

include_once UTILS_PATH . '/envSetter.util.php';

class Auth
{
    // Initialize the session
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Attempt login with username and password
    public static function login(PDO $pdo, string $username, string $password): bool
    {
        self::init();

        $stmt = $pdo->prepare('
            SELECT id, username, password, role, full_name
            FROM public."users"
            WHERE username = :username
            LIMIT 1
        ');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Never store raw hash in session
            $_SESSION['user'] = $user;
            return true;
        }

        return false;
    }

    // Return user data from session
    public static function user(): ?array
    {
        self::init();
        return $_SESSION['user'] ?? null;
    }

    // Check if user is logged in
    public static function check(): bool
    {
        self::init();
        return isset($_SESSION['user']);
    }

    // Log out the current user
    public static function logout(): void
    {
        self::init();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }
}
