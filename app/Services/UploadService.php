<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    private array $allowedImageMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private array $allowedDocMimes   = ['application/pdf', 'image/jpeg', 'image/png'];

    /**
     * Extensions that must never be saved, regardless of reported MIME type.
     * Guards against double-extension attacks (e.g., evil.php.jpg).
     */
    private array $blockedExtensions = [
        'php', 'php3', 'php4', 'php5', 'php7', 'phtml', 'phar',
        'exe', 'sh', 'bat', 'cmd', 'pl', 'py', 'rb', 'cgi',
        'htaccess', 'htpasswd', 'js', 'html', 'htm', 'xml',
    ];

    /**
     * Upload avatar — stored in public/storage/avatars/
     */
    public function uploadAvatar(UploadedFile $file, int $userId): string|false
    {
        return $this->upload($file, 'avatars', $this->allowedImageMimes, 2 * 1024 * 1024, "avatar_{$userId}");
    }

    /**
     * Upload gallery image
     */
    public function uploadGallery(UploadedFile $file, int $albumId): string|false
    {
        return $this->upload($file, "gallery/{$albumId}", $this->allowedImageMimes, 8 * 1024 * 1024, "gallery_{$albumId}_" . time());
    }

    /**
     * Upload document (PDF/image)
     */
    public function uploadDocument(UploadedFile $file, string $prefix = 'doc'): string|false
    {
        return $this->upload($file, 'documents', $this->allowedDocMimes, 5 * 1024 * 1024, $prefix . '_' . time());
    }

    /**
     * Upload logo
     */
    public function uploadLogo(UploadedFile $file): string|false
    {
        return $this->upload($file, 'logos', $this->allowedImageMimes, 2 * 1024 * 1024, 'logo_' . time());
    }

    /**
     * Upload story image
     */
    public function uploadStoryImage(UploadedFile $file): string|false
    {
        return $this->upload($file, 'stories', $this->allowedImageMimes, 5 * 1024 * 1024, 'story_' . time());
    }

    /**
     * Upload signature
     */
    public function uploadSignature(UploadedFile $file, int $userId): string|false
    {
        return $this->upload($file, 'signatures', $this->allowedImageMimes, 2 * 1024 * 1024, "signature_{$userId}");
    }

    /**
     * Upload for a raw $_FILES array (backwards compat for old controllers that haven't been refactored yet)
     */
    public function uploadRaw(array $file, string $subDir, array $allowedMimes, int $maxSize, string $namePrefix): string|false
    {
        if ($file['error'] !== UPLOAD_ERR_OK) return false;
        if ($file['size'] > $maxSize) return false;

        // Read real MIME from file bytes — not from HTTP header
        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if (!in_array($mimeType, $allowedMimes, true)) return false;

        // Block dangerous extensions
        $originalName = strtolower($file['name'] ?? '');
        if ($this->hasDangerousExtension($originalName)) return false;

        $ext      = $this->mimeToExt($mimeType);
        $filename = $namePrefix . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $destDir  = public_path('uploads/' . $subDir);

        if (!is_dir($destDir)) mkdir($destDir, 0755, true);
        if (!move_uploaded_file($file['tmp_name'], $destDir . DIRECTORY_SEPARATOR . $filename)) return false;

        // Remove executable permission
        @chmod($destDir . DIRECTORY_SEPARATOR . $filename, 0644);

        return $filename;
    }

    private function upload(UploadedFile $file, string $subDir, array $allowedMimes, int $maxSize, string $namePrefix): string|false
    {
        // 1. Size check
        if ($file->getSize() > $maxSize) return false;

        // 2. Block dangerous original extension before anything else
        $originalName = strtolower($file->getClientOriginalName());
        if ($this->hasDangerousExtension($originalName)) return false;

        // 3. Read real MIME from actual file bytes using finfo (not HTTP header or client claim)
        $realPath = $file->getRealPath();
        if ($realPath === false || !is_readable($realPath)) return false;
        try {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $realMime = $finfo->file($realPath);
        } catch (\Throwable $e) {
            Log::warning('UploadService finfo failed', ['err'=>$e->getMessage()]);
            return false;
        }
        if (!in_array($realMime, $allowedMimes, true)) return false;

        // 4. Cross-check: reported MIME must match real MIME
        $reportedMime = $file->getMimeType();
        if ($reportedMime !== $realMime) return false;

        // 5. Generate a safe randomized filename — no original name used
        $ext      = $this->mimeToExt($realMime);
        $filename = $namePrefix . '_' . bin2hex(random_bytes(8)) . '.' . $ext;

        // Store in public disk (storage/app/public/...)
        $file->storeAs($subDir, $filename, 'public');

        // Also ensure it exists in public/storage/... for environments without symlink
        $pubTarget = public_path("storage/{$subDir}");
        if (!is_dir($pubTarget)) {
            @mkdir($pubTarget, 0755, true);
        }
        @copy(storage_path("app/public/{$subDir}/{$filename}"), "{$pubTarget}/{$filename}");

        // Remove executable permission from the saved file
        @chmod("{$pubTarget}/{$filename}", 0644);

        return $filename;
    }

    /**
     * Checks if filename contains any dangerous extension (handles double-extension attacks).
     */
    private function hasDangerousExtension(string $filename): bool
    {
        // Split on ALL dots to catch double extensions like shell.php.jpg
        $parts = explode('.', $filename);
        // Check every segment after the first (all extensions)
        for ($i = 1; $i < count($parts); $i++) {
            if (in_array(strtolower($parts[$i]), $this->blockedExtensions, true)) {
                return true;
            }
        }
        return false;
    }

    private function mimeToExt(string $mime): string
    {
        return match ($mime) {
            'image/jpeg'      => 'jpg',
            'image/png'       => 'png',
            'image/webp'      => 'webp',
            'image/gif'       => 'gif',
            'application/pdf' => 'pdf',
            default           => 'bin',
        };
    }

    public function deleteFile(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        // Also try to remove from old uploads path for backwards compat
        $oldPath = public_path('uploads/' . $path);
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }
}
