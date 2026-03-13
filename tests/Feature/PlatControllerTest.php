<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Plat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_create_plat()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        
        $response = $this->actingAs($this->user)
            ->postJson('/api/plats', [
                'name' => 'Test Plat',
                'description' => 'Test Description',
                'price' => 15.99,
                'category_id' => $category->id
            ]);

        $response->assertStatus(201)
                ->assertJsonStructure(['id', 'name', 'price', 'category']);
    }

    public function test_user_can_list_their_plats()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        Plat::factory()->create(['user_id' => $this->user->id, 'category_id' => $category->id]);

        $response = $this->actingAs($this->user)->getJson('/api/plats');

        $response->assertStatus(200)
                ->assertJsonStructure(['data']);
    }

    public function test_user_cannot_view_other_users_plats()
    {
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);
        $plat = Plat::factory()->create(['user_id' => $otherUser->id, 'category_id' => $category->id]);

        $response = $this->actingAs($this->user)->getJson("/api/plats/{$plat->id}");

        $response->assertStatus(403);
    }
}