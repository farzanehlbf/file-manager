<?php
namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function all(array $filters = null, array $relations = null): Collection;
    public function create(array $data): Model;
    public function update(int $id, array $data): bool;
    public function find(int $id): ?Model;
    public function delete(int $id): bool;
    public function paginate(int $perPage = 15, array $filters = null, array $relations = null): LengthAwarePaginator;
}
