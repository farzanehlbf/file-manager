<?php
namespace App\Contracts\Repositories;

interface FolderRepositoryInterface extends BaseRepositoryInterface
{
    public function syncTags($folder, array $tagIds): void;
    public function detachTag($folderId, $tagId);


}
