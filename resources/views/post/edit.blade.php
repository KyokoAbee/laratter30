<x-app-layout>
    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('post.update', $post->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

                            <!-- 戻るボタン -->
                            <div class="mb-4">
                    <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        ダッシュボードに戻る
                    </a>
                </div>

            <div>
                <label for="content" class="block text-lg font-medium text-gray-700 mb-2">投稿内容</label>
                <textarea name="content" id="content" class="textarea textarea-bordered w-full p-3" required>{{ $post->content }}</textarea>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="btn" style="background-color: #66aa93; border-color: #66aa93; color: white;">編集して更新</button>
            </div>
        </form>
    </div>
</x-app-layout>