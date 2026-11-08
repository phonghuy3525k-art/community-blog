<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase; // <-- 1. Import trait này
use Tests\TestCase;

class PostFeatureTest extends TestCase
{
    use RefreshDatabase; // <-- 2. Khai báo sử dụng ở đây

    public function test_authenticated_user_can_create_post()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('my-posts.store'), [
            'title' => 'Bài viết thử nghiệm',
            'content' => 'Nội dung bài viết mẫu đầy đủ...',
            'category_id' => $category->id,
            'status' => 'published',
        ]);

        $response->assertRedirect(route('my-posts.index'));
        $this->assertDatabaseHas('posts', ['title' => 'Bài viết thử nghiệm']);
    }
}