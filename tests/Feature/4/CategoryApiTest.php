<?php

use Tests\TestCase;
use App\Models\User;
use DataSDK\Categories\Models\Categories;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryApiTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function createTestUser(): User
    {
        return $this->createUser();
    }


    public function test_fetches_categories_endpoints()
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $response = $this->getJson('/api/categories');
        $response->assertStatus(200);
    }

    public function test_searches_categories_by_type()
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $response = $this->postJson('/api/categories/type/search', []);
        $response->assertStatus(200);
    }

    public function test_can_see_categories_by_id()
    {
        $user = $this->createTestUser();
        $category = Categories::factory()->create();

        $response = $this->actingAs($user)->getJson("/api/categories/{$category->id}");
        $response->assertStatus(200);
    }

    public function test_can_see_categories_by_slug()
    {
        $user = $this->createTestUser();
        $category = Categories::factory()->create();

        $response = $this->actingAs($user)->getJson("/api/categories/{$category->slug}");
        $response->assertStatus(200);
    }

    public function test_gets_category_tree_by_type()
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $response = $this->postJson('/api/categories/type/tree', []);
        $response->assertStatus(200);
    }

    public function test_can_create_categories()
    {
        $user = $this->createTestUser();

        $data = [
            'name' => 'New Categories',
            'type' => 'test'
        ];

        $response = $this->actingAs($user)->postJson('/api/categories', $data);
        $response->assertStatus(201);
    }

    public function test_can_update_categories()
    {
        $user = $this->createTestUser();
        $category = Categories::factory()->create();

        $data = $category->toArray();

        $response = $this->actingAs($user)->patchJson("/api/categories/{$category->id}", $data);
        $response->assertStatus(200);
    }



    public function test_can_delete_categories()
    {
        $user = $this->createTestUser();
        $category = Categories::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/categories/{$category->id}");
        $response->assertNoContent();

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }
}
