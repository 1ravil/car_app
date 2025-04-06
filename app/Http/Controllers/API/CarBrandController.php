<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarBrandRequest;
use App\Http\Requests\UpdateCarBrandRequest;
use App\Models\CarBrand;
use App\Services\CarBrandService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CarBrandController extends Controller
{
    public function __construct(private CarBrandService $carBrandService)
    {
    }

    public function index(Request $request)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255'
        ]);

        $brands = CarBrand::when($request->title, function ($query, $title) {
            $query->where('title', 'ILIKE', "%{$title}%");
        })->get();

        return response()->json($brands);
    }

    public function store(StoreCarBrandRequest $request)
    {
        try {
            $brand = $this->carBrandService->createBrand($request->validated());
            return response()->json($brand, 201);
        } catch (\Exception $e) {
            throw new ApiException('internal_error', $e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $brand = $this->carBrandService->getBrand($id);
            return response()->json($brand);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException('Марка автомобиля не найдена');
        }
    }

    public function update(UpdateCarBrandRequest $request, $id)
    {
        try {
            $brand = $this->carBrandService->updateBrand($id, $request->validated());
            return response()->json($brand);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException('Марка автомобиля не найдена');
        } catch (\Exception $e) {
            throw new ApiException('internal_error', $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->carBrandService->deleteBrand($id);
            return response()->noContent();
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException('Марка автомобиля не найдена');
        } catch (\Exception $e) {
            throw new ApiException('internal_error', $e->getMessage());
        }
    }
}
