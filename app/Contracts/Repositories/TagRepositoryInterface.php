<?php
namespace App\Contracts\Repositories;

interface TagRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = null);
    public function create(array $data);
    public function find(int $id);
    public function update(array $data, int $id);
    public function delete(int $id);
}
