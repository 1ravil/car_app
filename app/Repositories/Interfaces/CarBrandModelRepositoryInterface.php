<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface CarBrandModelRepositoryInterface
{
    public function allForBrand(int $brandId, array $filters = []): Collection;
    public function createForBrand(int $brandId, array $data);
    public function findForBrand(int $brandId, int $modelId);
    public function updateForBrand(int $brandId, int $modelId, array $data);
    public function deleteForBrand(int $brandId, int $modelId);
}
