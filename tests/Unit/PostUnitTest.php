<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase; // <-- 1. Import trait
use Tests\TestCase;

class PostUnitTest extends TestCase
{
    use RefreshDatabase; // <-- 2. Khai báo sử dụng ở đây

    public function test_post_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $post->user);
        $this->assertEquals($user->id, $post->user->id);
    }

    public function test_author_can_update_their_own_post()
    {
        $author = User::factory()->create(['role' => 'member']);
        $otherUser = User::factory()->create(['role' => 'member']);
        
        $post = Post::factory()->create(['user_id' => $author->id]);

        $this->assertTrue($author->id === $post->user_id);
        $this->assertFalse($otherUser->id === $post->user_id);
    }
}