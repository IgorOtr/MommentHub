<?php

namespace App\Services;

use App\Models\Folder;
use App\Models\MediaFile;
use Illuminate\Support\Collection;

class MediaFileService
{
    public function __construct(
        protected GoogleDriveService $googleDrive,
    ) {}

    /**
     * Fetch the files from the folder's Google Drive link and sync them
     * into the media_files table, removing files that no longer exist.
     *
     * @return Collection<int, MediaFile>
     */
    public function syncFolder(Folder $folder): Collection
    {
        $folderId = $this->googleDrive->extractFolderId($folder->google_drive_url);

        if (! $folderId) {
            return $folder->mediaFiles;
        }

        $files = $this->googleDrive->listFiles($folderId);

        $syncedIds = collect($files)->map(function (array $file) use ($folder) {
            $mediaFile = MediaFile::updateOrCreate(
                ['google_drive_file_id' => $file['id']],
                [
                    'folder_id' => $folder->id,
                    'name' => $file['name'] ?? 'Sem nome',
                    'mime_type' => $file['mimeType'] ?? null,
                    'file_type' => $this->googleDrive->fileType($file['mimeType'] ?? ''),
                    'google_drive_url' => $file['webViewLink'] ?? null,
                    'thumbnail_url' => $file['thumbnailLink'] ?? null,
                    'preview_url' => "https://drive.google.com/file/d/{$file['id']}/preview",
                    'download_url' => $file['webContentLink'] ?? null,
                ]
            );

            return $mediaFile->id;
        });

        $folder->mediaFiles()
            ->whereNotIn('id', $syncedIds)
            ->delete();

        return $folder->mediaFiles()->get();
    }
}
