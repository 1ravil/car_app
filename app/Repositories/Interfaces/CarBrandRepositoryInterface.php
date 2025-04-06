<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface CarBrandRepositoryInterface
{
    public function all(array $filters = []): Collection;
    public function create(array $data);
    public function find(int $id);
    public function update(int $id, array $data);
    public function delete(int $id);
}
