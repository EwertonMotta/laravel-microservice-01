<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    protected $endpoint = '/categories';

    public function testGetAllCategories()
    {
        Category::factory()->count(6)->create();

        $response = $this->getJson($this->endpoint);
        $response->assertJsonCount(6, 'data');
        $response->assertStatus(200);
    }

    public function testErrorGetSingleCategory()
    {
        $category= 'fake-url';
        $response = $this->getJson("{$this->endpoint}/{$category}");
        $response->assertStatus(404);
    }

    public function testGetSingleCategory()
    {
        $category= Category::factory()->create();
        $response = $this->getJson("{$this->endpoint}/{$category->url}");
        $response->assertStatus(200);
    }

    public function testValidationStoreCategory()
    {
        $response = $this->postJson($this->endpoint, [
            'title' => '',
            'description' => ''
        ]);
        $response->assertStatus(422);
    }

    public function testStoreCategory()
    {
        $response = $this->postJson($this->endpoint, [
            'title' => 'Category 01',
            'description' => 'Descroption of category'
        ]);
        $response->assertStatus(201);
    }

    public function testUpdateCategory()
    {
        $category = Category::factory()->create();
        $data = [
            'title' => 'Title Updated',
            'description' => 'Description Updated',
        ];

        $response = $this->putJson("{$this->endpoint}/fake-category", $data);
        $response->assertStatus(404);

        $response = $this->putJson("{$this->endpoint}/{$category->url}", []);
        $response->assertStatus(422);

        $response = $this->putJson("{$this->endpoint}/{$category->url}", $data);
        $response->assertStatus(200);
    }

    public function testDeleteCategory()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/fake-category");
        $response->assertStatus(404);

        $response = $this->deleteJson("{$this->endpoint}/{$category->url}");
        $response->assertStatus(204);
    }
}
