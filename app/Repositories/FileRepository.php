<?php
namespace App\Repositories;

use App\Contracts\Repositories\FileRepositoryInterface;
use App\Models\File;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class FileRepository implements FileRepositoryInterface
{
    public function all(array $filters = null, array $relations = null): Collection
    {
        $query = File::query()->get();
        return $query;
    }
    public function paginate(int $perPage = 15, array $filters = null, array $relations = null): LengthAwarePaginator
    {
        $query = File::query();

        if (!empty($filters['name'])) {
            $query->where('original_name', 'like', '%' . $filters['name'] . '%');
        }

        return $query->paginate($perPage);
    }

    public function find(int $id): ?File
    {
        return File::with('folders', 'tags')->find($id);
    }

    public function create(array $data): File
    {
        return File::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $file = File::findOrFail($id);
        return $file->update($data);
    }

    public function delete(int $id): bool
    {
        $file = File::findOrFail($id);
        return $file->delete();
    }

    public function moveToFolders(File $file, array $folderIds): bool
    {
        $file->folders()->sync($folderIds);
        return true;
    }


    public function copyToFolders(File $file, array $folderIds): ?File
    {
        $copyData = $file->replicate()->toArray();
        unset($copyData['id'], $copyData['created_at'], $copyData['updated_at'], $copyData['deleted_at']);

        $copyData['stored_name'] = $this->generateNewStoredName($file->stored_name);
        $copyData['original_name'] = $file->original_name;

        $newFile = File::create($copyData);

        Storage::copy('files/' . $file->stored_name, 'files/' . $newFile->stored_name);

        $newFile->folders()->sync($folderIds);

        return $newFile;
    }


    protected function generateNewStoredName(string $oldName): string
    {
        return pathinfo($oldName, PATHINFO_FILENAME) . '_' . time() . '.' . pathinfo($oldName, PATHINFO_EXTENSION);
    }
}
