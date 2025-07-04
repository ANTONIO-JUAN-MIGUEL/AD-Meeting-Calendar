<?php
declare(strict_types=1);

include_once UTILS_PATH . '/auth.util.php';

class Upload
{
    public const MAX_BYTES = 2 * 1024 * 1024;

    public const ALLOWED_TYPES = [
        'image/jpeg' => '.jpg',
        'image/png' => '.png',
        'image/gif' => '.gif',
    ];

    public static function handle(array $file, PDO $pdo, string $type, string $relatedId): array
    {
        $user = Auth::user();
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'image_id' => null, 'error' => 'Upload error code ' . $file['error']];
        }

        if ($file['size'] > self::MAX_BYTES) {
            return ['success' => false, 'image_id' => null, 'error' => 'File must be 2 MB or smaller'];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!isset(self::ALLOWED_TYPES[$mime])) {
            return ['success' => false, 'image_id' => null, 'error' => 'Invalid file type'];
        }
        $ext = self::ALLOWED_TYPES[$mime];

        $role = $user['role'] ?? 'user';
        $basename = "{$user['username']}-{$user['id']}-" . preg_replace('/[^a-z0-9]+/i', '-', strtolower(trim($role)));
        $filename = $basename . $ext;

        $existingFile = BASE_PATH . "/uploads/{$type}/{$filename}";
        $itExist = false;
        if (file_exists($existingFile)) {
            if (!unlink($existingFile)) {
                return ['success' => false, 'image_id' => null, 'error' => 'Failed to delete existing file'];
            }
            $itExist = true;
        }

        $dir = BASE_PATH . "/uploads/{$type}";
        $dest = "{$dir}/{$filename}";
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return ['success' => false, 'image_id' => null, 'error' => 'Failed to move uploaded file'];
        }

        if ($type === 'profile') {
            $thumbPath = "{$dir}/thumbs/{$filename}";
            self::makeThumbnail($dest, $thumbPath, 150, 150);
        }

        try {
            $imageId = null;
            if ($itExist) {
                $stmt = $pdo->prepare("SELECT id FROM public.images WHERE user_id = :uid AND filename = :fn");
                $stmt->execute([':uid' => $relatedId, ':fn' => $filename]);
                $imageId = $stmt->fetchColumn();

                $stmt = $pdo->prepare("
                    UPDATE public.images
                    SET filename = :fn, filepath = :fp, mimetype = :mt, size_bytes = :sz, type = :tp
                    WHERE id = :uid
                    RETURNING id
                ");
                $stmt->execute([
                    ':uid' => $imageId,
                    ':fn' => $filename,
                    ':fp' => "/uploads/{$type}/{$filename}",
                    ':mt' => $mime,
                    ':sz' => $file['size'],
                    ':tp' => $type,
                ]);
                $imageId = $stmt->fetchColumn();
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO public.images (user_id, filename, filepath, mimetype, size_bytes, type)
                    VALUES (:uid, :fn, :fp, :mt, :sz, :tp)
                    RETURNING id
                ");
                $stmt->execute([
                    ':uid' => $relatedId,
                    ':fn' => $filename,
                    ':fp' => "/uploads/{$type}/{$filename}",
                    ':mt' => $mime,
                    ':sz' => $file['size'],
                    ':tp' => $type,
                ]);
                $imageId = $stmt->fetchColumn();
            }

            if ($type === 'profile') {
                $upd = $pdo->prepare("UPDATE public.\"users\" SET profile_image_id = :img WHERE id = :uid");
                $upd->execute([':img' => $imageId, ':uid' => $relatedId]);
            }

            return ['success' => true, 'image_id' => $imageId, 'error' => null, 'filename' => "/uploads/{$type}/{$filename}"];
        } catch (PDOException $e) {
            return ['success' => false, 'image_id' => null, 'error' => 'DB error: ' . $e->getMessage()];
        }
    }

    private static function makeThumbnail(string $src, string $dest, int $w, int $h): void
    {
        [$ow, $oh, $type] = getimagesize($src);
        switch ($type) {
            case IMAGETYPE_JPEG:
                $img = imagecreatefromjpeg($src);
                break;
            case IMAGETYPE_PNG:
                $img = imagecreatefrompng($src);
                break;
            case IMAGETYPE_GIF:
                $img = imagecreatefromgif($src);
                break;
            default:
                return;
        }

        $thumb = imagecreatetruecolor($w, $h);
        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $w, $h, $ow, $oh);
        imagejpeg($thumb, $dest);
        imagedestroy($img);
        imagedestroy($thumb);
    }
}
