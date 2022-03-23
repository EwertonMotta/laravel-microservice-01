<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    protected $endpoint = '/companies';

    public function testGetAllCompany()
    {
        Company::factory()->count(6)->create();

        $response = $this->getJson($this->endpoint);
        $response->assertJsonCount(6, 'data');
        $response->assertStatus(200);
    }

    public function testErrorGetSingleCompany()
    {
        $company= 'fake-uuid';
        $response = $this->getJson("{$this->endpoint}/{$company}");
        $response->assertStatus(404);
    }

    public function testGetSingleCompany()
    {
        $company= Company::factory()->create();
        $response = $this->getJson("{$this->endpoint}/{$company->uuid}");
        $response->assertStatus(200);
    }

    public function testValidationStoreCompany()
    {
        $response = $this->postJson($this->endpoint, [
            'title' => '',
            'email' => ''
        ]);
        $response->assertStatus(422);
    }

    public function testStoreCompany()
    {
        $category = Category::factory()->create();
        $response = $this->postJson($this->endpoint, [
            'category_id' => $category->id,
            'name' => 'Company 01',
            'email' => 'email@company01.com',
            'whatsapp' => '9564236589'
        ]);
        $response->assertStatus(201);
    }

    public function testUpdateCompany()
    {
        $company = Company::factory()->create();
        $category = Category::factory()->create();
        $data = [
            'category_id' => $category->id,
            'name' => 'Company 01',
            'email' => 'email@company01.com',
            'whatsapp' => '9564236589'
        ];

        $response = $this->putJson("{$this->endpoint}/fake-Company", $data);
        $response->assertStatus(404);

        $response = $this->putJson("{$this->endpoint}/{$company->uuid}", []);
        $response->assertStatus(422);

        $response = $this->putJson("{$this->endpoint}/{$company->uuid}", $data);
        $response->assertStatus(200);
    }

    public function testDeleteCompany()
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/fake-Company");
        $response->assertStatus(404);

        $response = $this->deleteJson("{$this->endpoint}/{$company->uuid}");
        $response->assertStatus(204);
    }
}
