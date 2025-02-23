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
            <div>
                <label for="content" class="block text-lg font-medium text-gray-700 mb-2">投稿内容</label>
                <textarea name="content" id="content" class="textarea textarea-bordered w-full p-3" required>{{ $post->content }}</textarea>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary btn-wide">編集</button>
            </div>
        </form>
    </div>
</x-app-layout>