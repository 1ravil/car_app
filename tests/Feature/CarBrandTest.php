<?php

namespace Tests\Feature;

use App\Models\CarBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarBrandTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_brands()
    {
        CarBrand::factory()->count(3)->create();

        $response = $this->getJson('/api/car-brands');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_filter_brands_by_title()
    {
        CarBrand::factory()->create(['title' => 'Lada']);
        CarBrand::factory()->create(['title' => 'Mazda']);

        $response = $this->getJson('/api/car-brands?title=ada');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'Lada']);
    }

    public function test_create_brand()
    {
        $data = ['title' => 'Toyota'];

        $response = $this->postJson('/api/car-brands', $data);

        $response->assertStatus(201)
            ->assertJson($data);

        $this->assertDatabaseHas('car_brands', $data);
    }

    public function test_show_brand()
    {
        $brand = CarBrand::factory()->create();

        $response = $this->getJson("/api/car-brands/{$brand->id}");

        $response->assertStatus(200)
            ->assertJson(['title' => $brand->title]);
    }

    public function test_update_brand()
    {
        $brand = CarBrand::factory()->create();
        $data = ['title' => 'Updated Brand'];

        $response = $this->putJson("/api/car-brands/{$brand->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);

        $this->assertDatabaseHas('car_brands', $data);
    }

    public function test_delete_brand()
    {
        $brand = CarBrand::factory()->create();

        $response = $this->deleteJson("/api/car-brands/{$brand->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('car_brands', ['id' => $brand->id]);
    }
}
