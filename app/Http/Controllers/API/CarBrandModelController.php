<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarModelRequest;
use App\Http\Requests\UpdateCarModelRequest;
use App\Services\CarBrandModelService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CarBrandModelController extends Controller
{
    public function __construct(private CarBrandModelService $carBrandModelService)
    {
    }

    public function index(Request $request, int $brandId)
    {
        try {
            $request->validate([
                'title' => 'sometimes|string|max:255'
            ]);

            $filters = $request->only(['title']);
            $models = $this->carBrandModelService->getAllModelsForBrand($brandId, $filters);

            return response()->json($models);
        } catch (ValidationException $e) {
            throw new ApiException(
                'bad_request',
                'Неверные параметры запроса',
                ['errors' => $e->errors()]
            );
        } catch (\Exception $e) {
            throw new ApiException('internal_error', $e->getMessage());
        }
    }

    public function store(StoreCarModelRequest $request, int $brandId)
    {
        try {
            $validated = $request->validated();
            $model = $this->carBrandModelService->createModelForBrand($brandId, $validated);

            return response()->json($model, 201);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException('Марка автомобиля не найдена');
        } catch (\Exception $e) {
            throw new ApiException('internal_error', $e->getMessage());
        }
    }

    public function show(int $brandId, int $modelId)
    {
        try {
            $model = $this->carBrandModelService->getModelForBrand($brandId, $modelId);
            return response()->json($model);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException('Модель автомобиля не найдена');
        } catch (\Exception $e) {
            throw new ApiException('internal_error', $e->getMessage());
        }
    }

    public function update(UpdateCarModelRequest $request, int $brandId, int $modelId)
    {
        try {
            $model = $this->carBrandModelService->updateModelForBrand(
                $brandId,
                $modelId,
                $request->validated()
            );

            return response()->json($model);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException('Модель автомобиля не найдена');
        } catch (\Exception $e) {
            throw new ApiException('internal_error', $e->getMessage());
        }
    }

    public function destroy(int $brandId, int $modelId)
    {
        try {
            $this->carBrandModelService->deleteModelForBrand($brandId, $modelId);
            return response()->noContent();
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException('Модель автомобиля не найдена');
        } catch (\Exception $e) {
            throw new ApiException('internal_error', $e->getMessage());
        }
    }
}
