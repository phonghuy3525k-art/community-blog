<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';
    protected $description = 'Tự động xuất bản các bài viết hẹn giờ đã đến hạn';

    public function handle()
    {
        $count = Post::where('status', 'scheduled')
            ->where('published_at', '<=', now())
            ->update([
                'status' => 'published',
                'published_at' => now()
            ]);

        $this->info("Đã xuất bản thành công {$count} bài viết hẹn giờ.");
    }
}