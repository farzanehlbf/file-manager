<?php

namespace App\Services;

use App\Contracts\Repositories\FileRepositoryInterface;
use Illuminate\Http\UploadedFile;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class FileService
{
    protected $fileRepo;

    public function __construct(FileRepositoryInterface $fileRepo)
    {
        $this->fileRepo = $fileRepo;
    }

    public function getAllFiles(int $perPage = 15, array $filters = null)
    {
        return $this->fileRepo->paginate($perPage, $filters);
    }



    public function update(File $file, array $data): bool
    {
        return $this->fileRepo->update($file->id, $data);
    }

    public function delete(File $file): bool
    {
        return $this->deleteFile($file);
    }

    public function deleteFile(File $file): bool
    {
        Storage::delete('files/' . $file->stored_name);
        return $this->fileRepo->delete($file->id);
    }

    public function upload(UploadedFile $uploadedFile, int $userId, array $folderIds = []): File
    {
        $storedName = $uploadedFile->store('files');

        $fileData = [
            'user_id' => $userId,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_name' => basename($storedName),
            'mime_type' => $uploadedFile->getClientMimeType(),
            'size' => $uploadedFile->getSize(),
        ];

        $file = $this->fileRepo->create($fileData);

        if ($folderIds) {
            $file->folders()->sync($folderIds);
        }

        return $file;
    }

    public function updateFile(int $id, array $data): bool
    {
        return $this->fileRepo->update($id, $data);
    }

    public function move(File $file, $folderIds): bool
    {

        if (is_int($folderIds)) {
            $folderIds = [$folderIds];
        }
        return $this->fileRepo->moveToFolders($file, $folderIds);
    }


    public function copy(File $file, int $folderId): ?File
    {
        return $this->fileRepo->copyToFolders($file, [$folderId]);
    }


    public function download(File $file)
    {
        return response()->download(
            storage_path('app/private/files/' . $file->stored_name),
            $file->original_name
        );
    }

    public function share(File $file, array $emails): bool
    {
        foreach ($emails as $email) {
            try {
                Mail::raw("فایل '{$file->original_name}' برای شما به اشتراک گذاشته شده است.", function ($message) use ($email) {
                    $message->to($email)->subject('اشتراک فایل');
                });
            } catch (\Throwable $e) {

            }
        }

        return true;
    }
}
