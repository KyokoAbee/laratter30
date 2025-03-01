<x-app-layout>
    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
        <h1>検索結果</h1>
        @if($posts->isEmpty())
            <p>該当する投稿がありません。</p>
        @else
            <ul>
                @foreach($posts as $post)
                    <li>
                        <a href="{{ route('post.show', $post->id) }}">
                            {{ Str::limit($post->content, 20, '...') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>