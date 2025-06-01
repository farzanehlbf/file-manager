<?php
namespace App\Services;

use App\Contracts\Repositories\TagRepositoryInterface;

class TagService
{
    protected $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getAllTags(int $perPage = 15, array $filters = null)
    {
        return $this->tagRepository->paginate($perPage, $filters);
    }

    public function createTag(array $data)
    {
        return $this->tagRepository->create($data);
    }

    public function findTag(int $id)
    {
        return $this->tagRepository->find($id);
    }

    public function updateTag(array $data, int $id)
    {
        return $this->tagRepository->update($data, $id);
    }

    public function deleteTag(int $id)
    {
        return $this->tagRepository->delete($id);
    }
}
