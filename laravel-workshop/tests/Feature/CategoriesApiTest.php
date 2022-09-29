<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductPriceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_endpoint_exists()
    {
        $response = $this->get('/api/categories');
        $response->assertStatus(200);
    }

    public function test_response_structure_contains_categories()
    {
        $response = $this->get('/api/categories');
        $response->assertExactJson([
            'categories' => [],
        ]);
    }

    public function test_returns_all_categories()
    {
        Category::factory(10)->create();
        $response = $this->get('/api/categories');
        $response->assertJsonCount(10, 'categories');
        $response->assertStatus(200);
    }

    public function test_categories_have_products()
    {
        $createdCategory = Category::factory()->create();

        $response = $this->get('/api/categories');
        $returnedCategory = $response->json('categories')[0];
        $this->assertEquals($createdCategory->name, $returnedCategory['name']);
        $this->assertEquals($createdCategory->id, $returnedCategory['id']);
        $this->assertIsArray($returnedCategory['products']);

        $products = Product::factory(10)->create([
            'category_id' => $createdCategory->id,
        ]);

        $response = $this->get('/api/categories');
        $returnedCategory = $response->json('categories')[0];
        $this->assertIsArray($returnedCategory['products']);
        $response->assertJsonCount(10, 'categories.0.products');
    }

    public function test_products_have_formatted_price()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->get('/api/categories');
        $returned = $response->json('categories.0.products.0');
        $this->assertArrayHasKey('formatted_price', $returned);
        $this->assertEquals((new ProductPriceService())->format($product->price_in_cents), $returned['formatted_price']);
        $this->assertArrayHasKey('price_in_cents', $returned);
        $this->assertIsInt($returned['price_in_cents']);
    }
}