<?php
namespace App\Repositories;

use App\Contracts\Repositories\FolderRepositoryInterface;
use App\Models\Folder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FolderRepository implements FolderRepositoryInterface
{
    public function all(array $filters = null, array $relations = null): Collection
    {
        $query = Folder::with('tags');

        if ($relations) {
            $query->with($relations);
        }

        if ($filters) {
            if (isset($filters['user_id'])) {
                $query->where('user_id', $filters['user_id']);
            }

            if (!empty($filters['name'])) {
                $query->where('name', 'like', '%'.$filters['name'].'%');
            }

            if (!empty($filters['tag'])) {
                $query->whereHas('tags', fn($q) => $q->where('name', $filters['tag']));
            }
        }

        return $query->get();
    }

    public function paginate(int $perPage = 15, array $filters = null, array $relations = null): LengthAwarePaginator
    {
        $query = Folder::with('tags');

        if ($relations) {
            $query->with($relations);
        }

        if ($filters) {
            if (isset($filters['user_id'])) {
                $query->where('user_id', $filters['user_id']);
            }

            if (!empty($filters['name'])) {
                $query->where('name', 'like', '%'.$filters['name'].'%');
            }

            if (!empty($filters['tag'])) {
                $query->whereHas('tags', fn($q) => $q->where('name', $filters['tag']));
            }
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): Folder
    {
        return Folder::create($data);
    }

    public function find(int $id): ?Folder
    {
        return Folder::find($id);
    }

    public function update(int $id, array $data): bool
    {
        $folder = Folder::findOrFail($id);
        return $folder->update($data);
    }

    public function delete(int $id): bool
    {
        $folder = Folder::findOrFail($id);
        return $folder->delete();
    }

    public function syncTags($folder, array $tagIds): void
    {
        $folder->tags()->sync($tagIds);
    }

    public function detachTag($folderId, $tagId)
    {
        $folder = Folder::findOrFail($folderId);
        return $folder->tags()->detach($tagId);
    }
}
