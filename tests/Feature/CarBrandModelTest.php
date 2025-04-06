<?php

namespace Tests\Feature;

use App\Models\CarBrand;
use App\Models\CarBrandModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarBrandModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_models_for_brand()
    {
        $brand = CarBrand::factory()->create();
        CarBrandModel::factory()->count(3)->create(['car_brand_id' => $brand->id]);

        $response = $this->getJson("/api/car-brands/{$brand->id}/models");

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_filter_models_by_title()
    {
        $brand = CarBrand::factory()->create();
        CarBrandModel::factory()->create(['car_brand_id' => $brand->id, 'title' => 'Granta']);
        CarBrandModel::factory()->create(['car_brand_id' => $brand->id, 'title' => 'Vesta']);

        $response = $this->getJson("/api/car-brands/{$brand->id}/models?title=esta");

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'Vesta']);
    }

    public function test_create_model_for_brand()
    {
        $brand = CarBrand::factory()->create();
        $data = ['title' => 'Granta'];

        $response = $this->postJson("/api/car-brands/{$brand->id}/models", $data);

        $response->assertStatus(201)
            ->assertJson($data);

        $this->assertDatabaseHas('car_brand_models', $data);
    }

    public function test_show_model_for_brand()
    {
        $brand = CarBrand::factory()->create();
        $model = CarBrandModel::factory()->create(['car_brand_id' => $brand->id]);

        $response = $this->getJson("/api/car-brands/{$brand->id}/models/{$model->id}");

        $response->assertStatus(200)
            ->assertJson(['title' => $model->title]);
    }

    public function test_update_model_for_brand()
    {
        $brand = CarBrand::factory()->create();
        $model = CarBrandModel::factory()->create(['car_brand_id' => $brand->id]);
        $data = ['title' => 'Updated Model'];

        $response = $this->putJson("/api/car-brands/{$brand->id}/models/{$model->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);

        $this->assertDatabaseHas('car_brand_models', $data);
    }

    public function test_delete_model_for_brand()
    {
        $brand = CarBrand::factory()->create();
        $model = CarBrandModel::factory()->create(['car_brand_id' => $brand->id]);

        $response = $this->deleteJson("/api/car-brands/{$brand->id}/models/{$model->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('car_brand_models', ['id' => $model->id]);
    }
}
