<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    protected const MEDIA_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'video/mp4',
        'video/quicktime',
    ];

    /**
     * Check whether the given URL is a valid, public Google Drive folder link.
     */
    public function isValidFolderUrl(string $url): bool
    {
        return $this->extractFolderId($url) !== null;
    }

    /**
     * Extract the Google Drive folder ID from a public folder URL.
     */
    public function extractFolderId(string $url): ?string
    {
        if (! str_contains($url, 'drive.google.com')) {
            return null;
        }

        if (preg_match('#/folders/([a-zA-Z0-9_-]+)#', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('#[?&]id=([a-zA-Z0-9_-]+)#', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * List the image/video files found in a public Google Drive folder.
     *
     * Requires a Google Drive API key (services.google_drive.api_key). Without
     * it, an empty list is returned so the UI can show a "not configured" state.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listFiles(string $folderId): array
    {
        $apiKey = config('services.google_drive.api_key');

        if (empty($apiKey)) {
            return [];
        }

        $response = Http::get('https://www.googleapis.com/drive/v3/files', [
            'key' => $apiKey,
            'q' => "'{$folderId}' in parents and trashed = false",
            'fields' => 'files(id, name, mimeType, thumbnailLink, webViewLink, webContentLink)',
            'pageSize' => 200,
        ]);

        if ($response->failed()) {
            Log::warning('Google Drive listing failed', [
                'folder_id' => $folderId,
                'status' => $response->status(),
            ]);

            return [];
        }

        return collect($response->json('files', []))
            ->filter(fn (array $file) => in_array($file['mimeType'] ?? null, self::MEDIA_MIME_TYPES, true))
            ->values()
            ->all();
    }

    public function fileType(string $mimeType): string
    {
        return str_starts_with($mimeType, 'video/') ? 'video' : 'image';
    }
}
