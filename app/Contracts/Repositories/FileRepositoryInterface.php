<?php
namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\File;

interface FileRepositoryInterface extends BaseRepositoryInterface
{

    public function moveToFolders(File $file, array $folderIds): bool;

    public function copyToFolders(File $file, array $folderIds): ?File;
}
