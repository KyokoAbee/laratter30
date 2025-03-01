<x-app-layout>
    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
        <h1>投稿の詳細</h1>
        <p>{{ $post->content }}</p>
        <a href="{{ route('post.edit', $post->id) }}" class="btn btn-primary">編集</a>
    </div>
</x-app-layout>