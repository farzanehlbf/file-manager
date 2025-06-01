<?php
namespace App\Repositories;

use App\Contracts\Repositories\FolderRepositoryInterface;
use App\Models\Folder;

class FolderRepository implements FolderRepositoryInterface
{
    public function paginate(int $perPage, array $filters = [])
    {
        $query = Folder::query()->where('user_id', $filters['user_id']);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (!empty($filters['tag'])) {
            $query->whereHas('tags', fn($q) => $q->where('name', $filters['tag']));
        }

        return $query->paginate($perPage);
    }

    public function create(array $data)
    {
        return Folder::create($data);
    }

    public function find($id)
    {
        return Folder::find($id);
    }

    public function update(array $data, $id)
    {
        $folder = Folder::findOrFail($id);
        $folder->update($data);
        return $folder;
    }

    public function delete($id)
    {
        $folder = Folder::findOrFail($id);
        return $folder->delete();
    }
}
