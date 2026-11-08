<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|max:1000',
        ]);

        Comment::create([
            'content' => $request->content,
            'post_id' => $post->id,
            'user_id' => auth()->id(),
        ]);

        // Ghi log thông báo qua queue hoặc hệ thống log[cite: 1]
        Log::info("Email thông báo: Có bình luận mới từ user ID " . auth()->id() . " cho bài viết ID " . $post->id);

        return back()->with('success', 'Đã thêm bình luận.');
    }

    public function destroy(Comment $comment)
    {
        if (auth()->id() === $comment->user_id || auth()->id() === $comment->post->user_id || auth()->user()->role === 'admin') {
            $comment->delete();
            return back()->with('success', 'Đã xóa bình luận.');
        }

        abort(403, 'Bạn không có quyền xóa bình luận này.');
    }
}