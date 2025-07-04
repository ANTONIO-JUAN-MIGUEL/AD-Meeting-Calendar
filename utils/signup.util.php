<?php
declare(strict_types=1);

include_once UTILS_PATH . '/envSetter.util.php';

class Signup
{
    public static function validate(array $data): array
    {
        $errors = [];

        $first_name = trim($data['first_name'] ?? '');
        $last_name = trim($data['last_name'] ?? '');
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';
        $role = trim($data['role'] ?? '');

        if ($first_name === '') {
            $errors[] = 'First name is required.';
        }
        if ($last_name === '') {
            $errors[] = 'Last name is required.';
        }
        if ($username === '') {
            $errors[] = 'Username is required.';
        }

        $validRoles = ['team lead', 'member'];
        if (!in_array($role, $validRoles, true)) {
            $errors[] = 'Role must be “team lead” or “member”.';
        }

        $pwLen = strlen($password);
        if (
            $pwLen < 8
            || !preg_match('/[A-Z]/', $password)
            || !preg_match('/[a-z]/', $password)
            || !preg_match('/\d/', $password)
            || !preg_match('/\W/', $password)
        ) {
            $errors[] = 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character.';
        }

        return $errors;
    }

    public static function create(PDO $pdo, array $data): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO public.\"users\"
              (first_name, middle_name, last_name, username, password, role)
            VALUES
              (:first, :middle, :last, :username, :password, :role)
        ");

        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt->execute([
            ':first' => trim($data['first_name']),
            ':middle' => trim($data['middle_name']) !== '' ? trim($data['middle_name']) : null,
            ':last' => trim($data['last_name']),
            ':username' => trim($data['username']),
            ':password' => $hashed,
            ':role' => trim($data['role']),
        ]);
    }
}
