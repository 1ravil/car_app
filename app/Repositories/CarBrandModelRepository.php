<?php

namespace App\Repositories;

use App\Models\CarBrand;
use App\Models\CarBrandModel;
use App\Repositories\Interfaces\CarBrandModelRepositoryInterface;
use Illuminate\Support\Collection;

class CarBrandModelRepository implements CarBrandModelRepositoryInterface
{
    public function allForBrand(int $brandId, array $filters = []): Collection
    {
        $query = CarBrandModel::where('car_brand_id', $brandId);

        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        return $query->get();
    }

    public function createForBrand(int $brandId, array $data)
    {
        $brand = CarBrand::findOrFail($brandId);
        return $brand->models()->create($data);
    }

    public function findForBrand(int $brandId, int $modelId)
    {
        return CarBrandModel::where('car_brand_id', $brandId)
            ->find($modelId);
    }

    public function updateForBrand(int $brandId, int $modelId, array $data)
    {
        $model = $this->findForBrand($brandId, $modelId);
        $model->update($data);
        return $model;
    }

    public function deleteForBrand(int $brandId, int $modelId)
    {
        $model = $this->findForBrand($brandId, $modelId);

        if ($model) {
            return $model->delete();
        }

        return false;
    }
}
