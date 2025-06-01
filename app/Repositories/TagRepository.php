<?php
namespace App\Repositories;

use App\Contracts\Repositories\TagRepositoryInterface;
use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{
    protected $model;

    public function __construct(Tag $tag)
    {
        $this->model = $tag;
    }

    public function paginate(int $perPage = 15, array $filters = null)
    {
        $query = $this->model->query();

        if ($filters && isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        return $query->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function update(array $data, int $id)
    {
        $tag = $this->model->find($id);
        if (!$tag) return null;

        $tag->update($data);

        return $tag;
    }

    public function delete(int $id)
    {
        $tag = $this->model->find($id);
        if (!$tag) return false;

        return $tag->delete();
    }
}
