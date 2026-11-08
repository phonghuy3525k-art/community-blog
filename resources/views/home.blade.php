<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Community Blog - Trang chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Community Blog</a>
            <div>
                @auth
                    <a href="{{ route('my-posts.index') }}" class="btn btn-outline-light btn-sm">Quản lý bài viết</a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-danger btn-sm">Quản trị Admin</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Đăng ký</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Bài viết mới nhất</h2>
                <hr>
                
                @if(isset($posts) && count($posts) > 0)
                    @foreach($posts as $post)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h3><a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">{{ $post->title }}</a></h3>
                                <p>{{ Str::limit($post->content, 150) }}</p>
                                <small class="text-muted">Đăng bởi: {{ $post->user->name ?? 'Ẩn danh' }} vào lúc {{ $post->created_at }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Chưa có bài viết nào.</p>
                @endif

            </div>
        </div>
    </div>
</body>
</html>