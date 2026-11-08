<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    // Chỉ tác giả bài viết hoặc Admin mới được cập nhật (sửa) bài viết
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->role === 'admin';
    }

    // Chỉ tác giả bài viết hoặc Admin mới được xóa bài viết
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->role === 'admin';
    }
}