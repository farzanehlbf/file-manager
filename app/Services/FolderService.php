<?php
namespace App\Services;

use App\Contracts\Repositories\FolderRepositoryInterface;

class FolderService
{
    protected $folderRepository;

    public function __construct(FolderRepositoryInterface $folderRepository)
    {
        $this->folderRepository = $folderRepository;
    }

    public function getFolders(array $filters = [], int $perPage = 15)
    {
        return $this->folderRepository->paginate($perPage, $filters);
    }

    public function createFolder(array $data)
    {
        return $this->folderRepository->create($data);
    }

    public function updateFolder($id, array $data)
    {
        return $this->folderRepository->update($data, $id);
    }

    public function deleteFolder($id)
    {
        return $this->folderRepository->delete($id);
    }

    public function findFolder($id)
    {
        return $this->folderRepository->find($id);
    }
}
