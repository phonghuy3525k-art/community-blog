<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $posts = auth()->user()->posts()->withTrashed()->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $post = Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'content' => $request->content,
            'thumbnail' => $thumbnailPath,
            'status' => $request->status,
            'published_at' => $request->status === 'scheduled' ? $request->published_at : ($request->status === 'published' ? now() : null),
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('my-posts.index')->with('success', 'Tạo bài viết thành công.');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published,scheduled',
        ]);

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $post->thumbnail = $thumbnailPath;
        }

        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'status' => $request->status,
            'published_at' => $request->status === 'scheduled' ? $request->published_at : ($request->status === 'published' ? now() : $post->published_at),
            'category_id' => $request->category_id,
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('my-posts.index')->with('success', 'Cập nhật bài viết thành công.');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect()->route('my-posts.index')->with('success', 'Đã chuyển bài viết vào thùng rác.');
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $this->authorize('update', $post);
        $post->restore();
        return redirect()->route('my-posts.index')->with('success', 'Đã khôi phục bài viết.');
    }

    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $this->authorize('delete', $post);
        $post->tags()->detach();
        $post->forceDelete();
        return redirect()->route('my-posts.index')->with('success', 'Đã xóa vĩnh viễn bài viết.');
    }
}