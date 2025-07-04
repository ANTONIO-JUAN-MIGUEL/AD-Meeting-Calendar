<?php
declare(strict_types=1);

include_once UTILS_PATH . '/envSetter.util.php';
include_once UTILS_PATH . '/auth.util.php';
include_once UTILS_PATH . '/upload.util.php';

class UpdateAccount
{
    public static function validate(PDO $pdo, array $data, ?array $file = null): array
    {
        $errors = [];

        $first_name = trim($data['first_name'] ?? '');
        $last_name = trim($data['last_name'] ?? '');
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';

        if ($first_name === '') {
            $errors[] = 'First name is required.';
        }
        if ($last_name === '') {
            $errors[] = 'Last name is required.';
        }
        if ($username === '') {
            $errors[] = 'Username is required.';
        }

        $currentUser = Auth::user();
        if ($username !== '' && $currentUser) {
            $stmt = $pdo->prepare("
                SELECT COUNT(*) FROM public.\"users\"
                WHERE username = :username AND id <> :current_id
            ");
            $stmt->execute([
                ':username' => $username,
                ':current_id' => $currentUser['id'],
            ]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Username already taken by another user.';
            }
        }

        if ($password !== '') {
            $pwLen = strlen($password);
            if (
                $pwLen < 8
                || !preg_match('/[A-Z]/', $password)
                || !preg_match('/[a-z]/', $password)
                || !preg_match('/\d/', $password)
                || !preg_match('/\W/', $password)
            ) {
                $errors[] = 'New password must be at least 8 characters and include uppercase, lowercase, number, and special character.';
            }
        }

        if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Error uploading image.';
            } else {
                $size = $file['size'];
                $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
                if ($size > 2 * 1024 * 1024) {
                    $errors[] = 'Profile image must be ≤ 2 MB.';
                }
                if (!in_array($mime, array_keys(Upload::ALLOWED_TYPES), true)) {
                    $errors[] = 'Profile image must be JPG, PNG, or GIF.';
                }
            }
        }

        return $errors;
    }

    public static function apply(PDO $pdo, array $data, ?array $file = null): void
    {
        $currentUser = Auth::user();
        if (!$currentUser) {
            throw new RuntimeException('No authenticated user.');
        }
        $fields = [];
        $params = [':id' => $currentUser['id']];

        $fields[] = 'first_name = :first';
        $params[':first'] = trim($data['first_name']);

        $fields[] = 'middle_name = :middle';
        $params[':middle'] = trim($data['middle_name']) !== ''
            ? trim($data['middle_name'])
            : null;

        $fields[] = 'last_name = :last';
        $params[':last'] = trim($data['last_name']);

        $fields[] = 'username = :username';
        $params[':username'] = trim($data['username']);

        if (trim($data['password']) !== '') {
            $fields[] = 'password = :password';
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $setClause = implode(",\n    ", $fields);
        $sql = "
            UPDATE public.\"users\"
               SET {$setClause}
             WHERE id = :id
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload = Upload::handle(
                $file,
                $pdo,
                'profile',
                $currentUser['id']
            );
            if ($upload['success']) {
                $_SESSION['user']['profile_image_id'] = $upload['image_id'];
            } else {
                error_log('[UpdateAccount] Image upload failed: ' . $upload['error']);
            }
        }
    }
}
