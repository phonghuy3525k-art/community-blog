<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- 1. Import trait
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory; // <-- 2. Khai báo sử dụng ở đây

    protected $fillable = ['title', 'slug', 'content', 'status', 'user_id', 'category_id'];

    // Các mối quan hệ (Relationships)
    public function user() { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function tags() { return $this->belongsToMany(Tag::class, 'post_tag'); }
    public function comments() { return $this->hasMany(Comment::class); }
}