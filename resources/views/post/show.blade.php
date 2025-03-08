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
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border-t-2 border-[#FFB997] mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-bold">{{ $post->user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $post->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                        <div class="text-sm">
                            @if($post->status)
                                <span class="bg-[#FFD791] text-gray-800 px-2 py-1 rounded-full ml-2">{{ $post->status }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <p>{{ $post->content }}</p>
                    </div>
                    <div class="mt-2">
                        @foreach ($post->post_tags as $tag)
                            <span class="inline-block bg-[#F5F5DC] rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">
                                #{{ $tag->tag }}
                            </span>
                        @endforeach
                    </div>
                    
                    <!-- アクションボタン -->
                    <div class="mt-4 flex justify-end space-x-2">
                        @if(Auth::id() == $post->user_id)
                            <a href="{{ route('post.edit', $post->id) }}" class="btn bg-[#30466f] hover:bg-[#30466f]/90 border-[#30466f] text-white">編集</a>
                        @endif
                        <a href="{{ route('recommendation.create', $post->id) }}" class="btn bg-[#30466f] hover:bg-[#30466f]/90 border-[#30466f] text-white">この投稿に返信する</a>
                    </div>
                </div>

                <!-- おすすめ本の一覧 -->
                <h3 class="text-xl font-bold mb-4">おすすめの本</h3>
                
                <!-- ベストレコメンドが選ばれていない場合のメッセージ（投稿者向け） -->
                @if($post->recommendations->isEmpty())
                    <p class="text-gray-500 text-center py-8">まだおすすめの本がありません。最初の返信をしてみましょう！</p>
                @elseif(!$post->recommendations->contains('is_best', true) && Auth::id() == $post->user_id)
                    <div class="bg-[#FFD791]/20 p-4 rounded-lg mb-4">
                        <p class="text-center">気に入ったレコメンドがあれば「ベストレコメンドに選ぶ」ボタンをクリックしてください。</p>
                    </div>
                @endif
                
<!-- レコメンド一覧 -->
@if(!$post->recommendations->isEmpty())
    <div class="space-y-4">
        <!-- まずベストレコメンドを表示 -->
        @foreach($post->recommendations->where('is_best', true) as $recommendation)
            <div class="p-4 border-2 border-[#FFD791] rounded-lg bg-[#FFD791]/10 flex flex-col md:flex-row gap-4">


                <div class="w-full md:w-3/4">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center">
                            <span class="bg-[#FFD791] text-gray-800 px-3 py-1 rounded-full text-sm font-bold mr-2">ベストレコメンド</span>
                            <h4 class="font-bold">{{ $recommendation->user->name }}さんからのおすすめ</h4>
                        </div>
                        <p class="text-sm text-gray-500">{{ $recommendation->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                    
                    <h4 class="text-lg font-bold">{{ $recommendation->book_title }}</h4>
                    <p class="text-sm text-gray-500 mb-2">{{ $recommendation->book_author }}</p>
                    <p class="text-sm text-gray-700 mb-4">{{ $recommendation->book_description }}</p>
                    
                    <div class="mt-2 border-t pt-3">
                        <p class="text-sm font-semibold mb-1">{{ $recommendation->user->name }}さんからのコメント：</p>
                        <p>{{ $recommendation->reason }}</p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="https://www.amazon.co.jp/s?k={{ urlencode($recommendation->book_title . ' ' . $recommendation->book_author) }}" 
                           target="_blank" rel="noopener noreferrer" 
                           class="btn bg-[#A7E8BD] hover:bg-[#A7E8BD]/90 border-[#A7E8BD] text-gray-800 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Amazonで見る
                        </a>
                    </div>
                </div>

                    <!-- サムネイルがある場合、右側に表示 -->
                    @if($recommendation->book->thumbnail)
                    <div class="w-full md:w-1/4 flex justify-end">
                    <img src="{{ $recommendation->book->thumbnail }}" 
                        alt="{{ $recommendation->book->title }}" 
                        class="rounded-lg shadow-md max-w-full">
                </div>
            @endif

            </div>
        @endforeach
    </div>
                        
        <!-- 残りのレコメンドを表示 -->

        @foreach($post->recommendations->where('is_best', false) as $recommendation)
            <div class="p-4 border rounded-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold">{{ $recommendation->user->name }}さんからのおすすめ</h4>
                        <p class="text-sm text-gray-500">{{ $recommendation->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                    <!-- 投稿者のみにベストレコメンド選択ボタンを表示 -->
                    @if(Auth::id() == $post->user_id && !$recommendation->is_best)
                        <form action="{{ route('recommendation.setBest', [$post->id, $recommendation->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm bg-[#30466f] hover:bg-[#30466f]/90 border-[#30466f] text-white">
                                ベストレコメンドに選ぶ
                            </button>
                        </form>
                    @endif
                </div>
                                
                <div class="mt-3">
                    <p>{{ $recommendation->reason }}</p>
                </div>
            </div>
        @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>