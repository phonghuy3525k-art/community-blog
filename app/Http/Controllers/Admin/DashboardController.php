<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalPosts = Post::where('status', 'published')->count();
        $totalComments = Comment::count();
        
        // Top 5 bài viết nhiều bình luận nhất[cite: 1]
        $topPosts = Post::withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalUsers', 'totalPosts', 'totalComments', 'topPosts'));
    }
}
