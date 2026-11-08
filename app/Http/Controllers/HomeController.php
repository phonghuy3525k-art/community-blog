<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo query lấy bài viết có trạng thái published và thời gian xuất bản hợp lệ
        $query = Post::with(['user', 'category', 'tags'])
            ->where('status', 'published')
            ->where('published_at', '<=', now());

        // Tìm kiếm theo từ khóa tiêu đề
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo Tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Phân trang kết quả (mỗi trang 10 bài)
        $posts = $query->latest('published_at')->paginate(10)->withQueryString();

        // Lấy danh sách danh mục và tag để hiển thị bộ lọc trên view
        $categories = Category::all();
        $tags = Tag::all();

        return view('home', compact('posts', 'categories', 'tags'));
    }

    public function show($slug)
    {
        // Lấy chi tiết bài viết kèm bình luận và tác giả của bình luận
        $post = Post::with(['user', 'category', 'tags', 'comments.user'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }
}
