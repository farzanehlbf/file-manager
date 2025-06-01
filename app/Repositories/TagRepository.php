<?php

namespace App\Repositories;

use App\Contracts\Repositories\TagRepositoryInterface;
use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TagRepository implements TagRepositoryInterface
{
    protected Tag $model;

    public function __construct(Tag $tag)
    {
        $this->model = $tag;
    }

    public function all(array $filters = null, array $relations = null): Collection
    {
        $query = $this->model->newQuery();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    public function paginate(int $perPage = 15, array $filters = null, array $relations = null): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): Tag
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?Tag
    {
        return $this->model->find($id);
    }

    public function update(int $id, array $data): bool
    {
        $tag = $this->model->find($id);

        if (!$tag) {
            return false;
        }

        return $tag->update($data);
    }

    public function delete(int $id): bool
    {
        $tag = $this->model->find($id);

        if (!$tag) {
            return false;
        }

        return $tag->delete();
    }
}
