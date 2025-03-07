<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- 戻るボタン -->
                <div class="mb-4">
                    <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        一覧に戻る
                    </a>
                </div>

                <!-- 投稿内容 -->
                <div class="mb-6 p-4 border dark:border-gray-700 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-bold">{{ $post->user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $post->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                        <div class="text-sm">
                            @if($post->status)
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full ml-2">{{ $post->status }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <p>{{ $post->content }}</p>
                    </div>
                    <div class="mt-2">
                        @foreach ($post->post_tags as $tag)
                            <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">
                                #{{ $tag->tag }}
                            </span>
                        @endforeach
                    </div>
                    
                    <!-- アクションボタン -->
                    <div class="mt-4 flex justify-end space-x-2">
                        @if(Auth::id() == $post->user_id)
                            <a href="{{ route('post.edit', $post->id) }}" class="btn btn-outline btn-info btn-sm">編集</a>
                        @endif
                        <a href="{{ route('recommendation.create', $post->id) }}" class="btn btn-outline btn-info">この投稿に返信する</a>
                    </div>
                </div>

                <!-- おすすめ本の一覧 -->
                <h3 class="text-xl font-bold mb-4">おすすめの本</h3>
                
                @if($post->recommendations->isEmpty())
                    <p class="text-gray-500 text-center py-8">まだおすすめの本がありません。最初の返信をしてみましょう！</p>
                @else
                    <div class="space-y-4">
                        @foreach($post->recommendations as $recommendation)
                            <div class="p-4 border rounded-lg">
                                <div class="flex flex-col md:flex-row gap-4">
                                    @if($recommendation->book_thumbnail)
                                        <div class="w-full md:w-1/5">
                                            <img src="{{ $recommendation->book_thumbnail }}" alt="{{ $recommendation->book_title }}" class="rounded-lg">
                                        </div>
                                    @endif
                                    <div class="w-full md:w-4/5">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-bold">{{ $recommendation->book_title }}</h4>
                                                <p class="text-sm text-gray-500">{{ $recommendation->book_author }}</p>
                                            </div>
                                            <p class="text-sm text-gray-500">{{ $recommendation->created_at->format('Y/m/d H:i') }}</p>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-700">{{ $recommendation->book_description }}</p>
                                        </div>
                                        
                                        <div class="mt-4 border-t pt-3">
                                            <p class="text-sm font-semibold">{{ $recommendation->user->name }}さんからのコメント：</p>
                                            <p>{{ $recommendation->reason }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>