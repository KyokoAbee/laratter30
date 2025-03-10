<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">{{ $title ?? 'あなたの返信一覧' }}</h2>

                <!-- 戻るボタン -->
                <div class="mb-4">
                    <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        ダッシュボードに戻る
                    </a>
                </div>

                @if($recommendations->isEmpty())
                    <p class="text-gray-500 text-center py-8">まだレコメンドがありません。</p>
                @else
                    <div class="space-y-4">
                        @foreach($recommendations as $recommendation)
                            <div class="p-4 border rounded-lg {{ $recommendation->is_best ? 'border-[#FFD791] bg-[#FFD791]/10' : '' }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold">{{ $recommendation->post->user->name }}さんの投稿へのレコメンド</h4>
                                        <p class="text-sm text-gray-500">{{ $recommendation->created_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                    @if($recommendation->is_best)
                                        <span class="bg-[#FFD791] text-gray-800 px-3 py-1 rounded-full text-sm font-bold">ベストレコメンド</span>
                                    @endif
                                </div>
                                
                                <!-- 投稿の内容を簡潔に表示 -->
                                <div class="mt-2 p-2 bg-gray-50 rounded">
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">投稿内容：</span>
                                        {{ Str::limit($recommendation->post->content, 100) }}
                                    </p>
                                </div>
                                
                                <!-- 本の情報 -->
                                <div class="mt-4 flex gap-4">
                                    @if($recommendation->book && $recommendation->book->thumbnail)
                                        <div class="w-1/6">
                                            <img src="{{ $recommendation->book->thumbnail }}" 
                                                alt="{{ $recommendation->book_title ?? $recommendation->book->title }}" 
                                                class="rounded-lg shadow-sm max-w-full">
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h5 class="font-semibold text-lg">{{ $recommendation->book_title }}</h5>
                                        <p class="text-sm text-gray-700">{{ $recommendation->book_author }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($recommendation->book_description, 150) }}</p>
                                    </div>
                                </div>
                                
                                <!-- レコメンド理由 -->
                                <div class="mt-3 p-2 bg-gray-50 rounded">
                                    <p class="text-sm font-medium">あなたのコメント：</p>
                                    <p>{{ $recommendation->reason }}</p>
                                </div>
                                
                                <div class="mt-3 flex justify-end">
                                    <a href="{{ route('post.show', $recommendation->post_id) }}" class="btn btn-sm bg-[#30466f] hover:bg-[#30466f]/90 border-[#30466f] text-white">
                                        投稿を見る
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        {{ $recommendations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>