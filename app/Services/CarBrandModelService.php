<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
use App\Repositories\Interfaces\CarBrandModelRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarBrandModelService
{
    public function __construct(
        private CarBrandModelRepositoryInterface $carBrandModelRepository
    ) {}

    public function getAllModelsForBrand(int $brandId, array $filters = [])
    {
        try {
            return $this->carBrandModelRepository->allForBrand($brandId, $filters);
        } catch (\Exception $e) {
            Log::error("Failed to get models for brand {$brandId}: " . $e->getMessage());
            throw new ApiException('internal_error', 'Ошибка при получении списка моделей');
        }
    }

    public function createModelForBrand(int $brandId, array $data)
    {
        DB::beginTransaction();
        try {
            $data['car_brand_id'] = $brandId;
            $model = $this->carBrandModelRepository->createForBrand($brandId, $data);
            DB::commit();
            return $model;
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException('Марка автомобиля не найдена');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Model creation failed for brand {$brandId}: " . $e->getMessage());
            throw new ApiException('internal_error', 'Ошибка при создании модели');
        }
    }

    public function getModelForBrand(int $brandId, int $modelId)
    {
        try {
            $model = $this->carBrandModelRepository->findForBrand($brandId, $modelId);

            if (!$model || $model->car_brand_id != $brandId) {
                throw new NotFoundException('Модель автомобиля не найдена');
            }

            return $model;
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error("Failed to get model {$modelId} for brand {$brandId}: " . $e->getMessage());
            throw new ApiException('internal_error', 'Ошибка при получении модели');
        }
    }

    public function updateModelForBrand(int $brandId, int $modelId, array $data)
    {
        DB::beginTransaction();
        try {
            $model = $this->getModelForBrand($brandId, $modelId);
            $updatedModel = $this->carBrandModelRepository->updateForBrand($brandId, $modelId, $data);
            DB::commit();
            return $updatedModel;
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update model {$modelId} for brand {$brandId}: " . $e->getMessage());
            throw new ApiException('internal_error', 'Ошибка при обновлении модели');
        }
    }

    public function deleteModelForBrand(int $brandId, int $modelId): void
    {
        DB::beginTransaction();
        try {
            $model = $this->getModelForBrand($brandId, $modelId);
            $this->carBrandModelRepository->deleteForBrand($brandId, $modelId);
            DB::commit();
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete model {$modelId} for brand {$brandId}: " . $e->getMessage());
            throw new ApiException('internal_error', 'Ошибка при удалении модели');
        }
    }
}
