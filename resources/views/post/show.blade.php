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
                            <a href="{{ route('post.edit', $post->id) }}" class="btn" style="background-color: #66aa93; border-color: #66aa93; color: white;">編集する</a>
                        @else
                            <a href="{{ route('recommendation.create', $post->id) }}" class="btn" style="background-color: #66aa93; border-color: #66aa93; color: white;">この投稿に返信する</a>
                        @endif
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
        <!-- ベストレコメンドの投稿 is_best で判断-->
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

                        <!-- お礼コメント -->
                         <!-- お礼コメントボタン（投稿者のみ表示） -->
                        @if(Auth::id() == $post->user_id && !isset($recommendation->evaluation))
                            <button type="button" 
                                    class="btn bg-[#FFB997] hover:bg-[#FFB997]/90 border-[#FFB997] text-gray-800 ml-2 inline-flex items-center"
                                    x-data=""
                                    x-on:click="$dispatch('open-modal', 'thank-you-modal-{{ $recommendation->id }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                お礼コメントを送る
                            </button>
                        @endif

                        <!-- お礼コメント表示 -->
                        @if(isset($recommendation->evaluation) && $recommendation->evaluation->thank_you_message)
                            <div class="mt-4 bg-[#FFB997]/10 p-3 rounded-lg border border-[#FFB997]">
                                <p class="text-sm font-semibold mb-1">{{ $post->user->name }}さんからのお礼コメント：</p>
                                <p class="text-sm">{{ $recommendation->evaluation->thank_you_message }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $recommendation->evaluation->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                        @endif
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
                        
    <!-- ベストレコメンドになっていない投稿 is_best がfalse-->
    @foreach($post->recommendations->where('is_best', false) as $recommendation)
        <div class="p-4 border rounded-lg">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-bold">{{ $recommendation->user->name }}さんからのおすすめ</h4>
                    <p class="text-sm text-gray-500">{{ $recommendation->created_at->format('Y/m/d H:i') }}</p>
                </div>
                <div class="flex space-x-2">
                    <!-- 投稿者のみにベストレコメンド選択ボタンを表示 -->
                    @if(Auth::id() == $post->user_id && !$recommendation->is_best)
                        <form action="{{ route('recommendation.setBest', [$post->id, $recommendation->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm bg-[#30466f] hover:bg-[#30466f]/90 border-[#30466f] text-white">
                                ベストレコメンドに選ぶ
                            </button>
                        </form>
                    @endif
                    
                    <!-- 返信の投稿者のみに削除ボタンを表示（かつis_bestがfalseの場合のみ） -->
                    <!-- : インラインスタイルを追加 -->
                    @if(Auth::id() == $recommendation->user_id && !$recommendation->is_best)
                        <form action="{{ route('recommendation.destroy', $recommendation->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('この返信を削除してもよろしいですか？')" 
                                    class="btn btn-sm text-white"
                                    style="background-color: #dc2626; border-color: #dc2626;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
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

        <!-- お礼コメントモーダル -->
        @foreach($post->recommendations->where('is_best', true) as $recommendation)
            <x-modal name="thank-you-modal-{{ $recommendation->id }}" :show="false" maxWidth="md">
                <form method="POST" action="{{ route('reply.thank-you') }}">
                    @csrf
                    <input type="hidden" name="recommendation_id" value="{{ $recommendation->id }}">
                    
                    <div class="p-6">

                    <!-- フラッシュメッセージ -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $recommendation->user->name }}さんへのお礼コメント
                        </h2>

                        <div class="mt-6">
                            <textarea
                                name="thank_you_message"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="4"
                                placeholder="心を込めたお礼メッセージを書きましょう"
                                required
                            ></textarea>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150" x-on:click="$dispatch('close-modal')">
                                キャンセル
                            </button>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#30466f] hover:bg-[#30466f]/90 border border-[#30466f] rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-3">
                                送信する
                            </button>
                        </div>
                    </div>
                </form>
            </x-modal>
        @endforeach
</x-app-layout>
