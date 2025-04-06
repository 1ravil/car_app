<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
use App\Models\CarBrand;
use App\Repositories\Interfaces\CarBrandRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarBrandService
{
    public function __construct(
        private CarBrandRepositoryInterface $carBrandRepository
    ) {}

    public function getAllBrands(array $filters = [])
    {
        try {
            return $this->carBrandRepository->all($filters);
        } catch (\Exception $e) {
            Log::error("Failed to get brands: " . $e->getMessage());
            throw new ApiException('internal_error', 'Ошибка при получении списка марок');
        }
    }

    public function createBrand(array $data): CarBrand
    {
        DB::beginTransaction();
        try {
            $brand = $this->carBrandRepository->create($data);
            DB::commit();
            return $brand;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Brand creation failed: " . $e->getMessage());
            throw new ApiException('internal_error', 'Ошибка при создании марки');
        }
    }

    public function getBrand(int $id): CarBrand
    {
        try {
            $brand = $this->carBrandRepository->find($id);

            if (!$brand) {
                throw new NotFoundException('Марка автомобиля не найдена');
            }

            return $brand;
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error("Failed to get brand {$id}: " . $e->getMessage());
            throw new ApiException('internal_error', 'Ошибка при получении марки');
        }
    }

    public function updateBrand(int $id, array $data): CarBrand
    {
        DB::beginTransaction();
        try {
            $brand = $this->getBrand($id);
            $updatedBrand = $this->carBrandRepository->update($brand->id, $data);
            DB::commit();
            return $updatedBrand;
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update brand {$id}: " . $e->getMessage());
            throw new ApiException('internal_error', 'Ошибка при обновлении марки');
        }
    }

    public function deleteBrand(int $id): void
    {
        DB::beginTransaction();
        try {
            $brand = CarBrand::find($id);

            if (!$brand) {
                throw new NotFoundException('Марка автомобиля не найдена');
            }

            // Каскадное удаление моделей происходит на уровне БД
            $brand->delete();
            DB::commit();
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete brand {$id}: " . $e->getMessage());
            throw new ApiException('internal_error', 'Ошибка при удалении марки');
        }
    }
}
