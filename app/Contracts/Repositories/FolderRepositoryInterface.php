<?php
namespace App\Contracts\Repositories;

interface FolderRepositoryInterface
{
    public function paginate(int $perPage, array $filters = []);
    public function create(array $data);
    public function find($id);
    public function update(array $data, $id);
    public function delete($id);

    public function syncTags($folder, array $tagIds): void;
    public function detachTag($folderId, $tagId);


}
