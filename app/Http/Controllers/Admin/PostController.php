<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Log; // <-- Import thư viện Log

class PostController extends Controller
{
    public function destroy(Post $post)
    {
        // 1. Ghi log lại sự kiện quan trọng trước khi xóa
        Log::warning("Sự kiện nguy hiểm: Admin [ID: " . auth()->id() . ", Email: " . auth()->user()->email . "] đã xóa vĩnh viễn bài viết vi phạm [ID: {$post->id} - Tiêu đề: {$post->title}]");

        // 2. Thực hiện xóa bài viết
        $post->delete();

        return back()->with('success', 'Đã xóa bài viết vi phạm.');
    }

    public function edit(Post $post)
{
    // Kiểm tra quyền bằng Policy
    if ($this->authorize('update', $post)) {
        // ... logic hiển thị form sửa
    } else {
        // Ghi log cảnh báo hành vi cố ý vi phạm quyền hạn
        Log::warning("Cảnh báo bảo mật: User [ID: " . auth()->id() . "] cố gắng chỉnh sửa bài viết [ID: {$post->id}] không thuộc quyền sở hữu.");
        abort(403, 'Bạn không có quyền.');
    }
}
}