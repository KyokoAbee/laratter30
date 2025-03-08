
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

                    <div class="mb-4 flex justify-end">
                    <a href="{{ route('post.create') }}" class="btn bg-[#30466f] hover:bg-[#30466f]/90 border-[#30466f] text-white">新規投稿</a>
                    </div>

                    @if($posts->isEmpty())
                        <p class="text-center py-4">投稿がまだありません。</p>
                    @else
                        @foreach ($posts as $post)
                            <div class="mb-6 p-4 border dark:border-gray-700 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <!-- Usersテーブルからユーザー名を表示 -->
                                        <h3 class="font-bold">{{ $post->user->name }}</h3> 
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                    <div class="text-sm">
                                        <!-- Posts からstatusの情報を表示 -->
                                        @if($post->status)
                                            <span class="bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100 px-2 py-1 rounded-full ml-2">{{ $post->status }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <!-- Posts からcontent の情報を表示 -->
                                    <p>{{ $post->content }}</p>
                                </div>
                                <div class="mt-2">
                                    @foreach ($post->post_tags as $tag)
                                        <span class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2">
                                            #{{ $tag->tag }}
                                        </span>
                                    @endforeach
                                </div>
                                <div class="mt-3 text-sm text-gray-600 dark:text-gray-400 flex justify-between">
                                    <span>{{ $post->recommendations->count() }} 件のおすすめ本</span>
                                    <a href="{{ route('post.show', $post->id) }}" class="text-blue-500 hover:underline">詳細を見る</a>
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