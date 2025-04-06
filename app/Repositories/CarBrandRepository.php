<?php

namespace App\Repositories;

use App\Models\CarBrand;
use App\Repositories\Interfaces\CarBrandRepositoryInterface;
use Illuminate\Support\Collection;

class CarBrandRepository implements CarBrandRepositoryInterface
{
    public function all(array $filters = []): Collection
    {
        $query = CarBrand::query();

        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        return $query->get();
    }

    public function create(array $data)
    {
        return CarBrand::create($data);
    }

    public function find(int $id)
    {
        return CarBrand::with('models')->findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $brand = $this->find($id);
        $brand->update($data);
        return $brand;
    }

    public function delete(int $brandId)
    {
        $brand = $this->find($brandId);
        return $brand->delete();
    }
}
