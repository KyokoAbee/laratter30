<x-app-layout>
    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
        <form action="{{ route('post.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="content" class="block text-lg font-medium text-gray-700 mb-2">読みたい本のジャンル、テーマを教えてください。</label>
                <input type="text" name="content" id="content" class="input input-bordered w-full p-3" placeholder="例: 旅したくなるような小説、クスっと笑えるエッセイ" required>
            </div>
            
            <div class="flex justify-end">
                <button class="btn btn-outline btn-info">投稿</button>
            </div>
        </form>
    </div>
</x-app-layout>