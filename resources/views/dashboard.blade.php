
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('投稿一覧') }}


        </h2>
    </x-slot>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($posts->isEmpty())
                        <p class="text-center py-4">投稿がまだありません。</p>
                    @else

            <!-- 上部にもページネーションを追加 -->
            <div class="mb-4">
                {{ $posts->links() }}
            </div>

    @foreach ($posts as $post)
    
    <div class="mb-6 p-4 border dark:border-gray-700 rounded-lg">
        <!-- タグ情報を先に表示 -->
        <div class="mb-3">
            @foreach ($post->post_tags as $tag)
                <span class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2">
                    #{{ $tag->tag }}
                </span>
            @endforeach
        </div>
        
        <!-- 投稿内容を40字で切り詰めて表示 -->
        <div class="text-lg font-medium mb-3">
            <p>{{ Str::limit($post->content, 100, '...') }}</p>
        </div>

        <!-- ステータス表示 -->
        @if($post->status)
            <div class="mb-2">
                <span class="bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100 px-2 py-1 rounded-full">{{ $post->status }}</span>
            </div>
        @endif
        
        <!-- 詳細リンク -->
        <div class="mt-3 text-sm text-blue-500 hover:underline">
            <a href="{{ route('post.show', $post->id) }}">詳細を見る</a>
        </div>
        
        <!-- ユーザー情報と時間を縦に並べて控えめに -->
        <div class="mt-3 flex justify-between items-end text-xs text-gray-500 dark:text-gray-400 border-t pt-2">
            <div>
                <div>{{ $post->user->name }}</div>
                <div>{{ $post->created_at->format('n/j H:i') }}</div>
            </div>
            <div>
                <span>{{ $post->recommendations->count() }} 件のおすすめ本</span>
            </div>
        </div>
    </div>
    @endforeach
                        
                        <div class="mt-4">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>