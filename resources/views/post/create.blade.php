<x-app-layout>
    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
        <form action="{{ route('post.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="content" class="block text-lg font-medium text-gray-700 mb-2">読みたい本のジャンル、テーマを教えてください。</label>
                <textarea 
                    name="content" 
                    id="content" 
                    rows="4" 
                    class="textarea textarea-bordered w-full p-3" 
                    placeholder="例: 旅したくなるような小説、クスっと笑えるエッセイ" 
                    required
                ></textarea>
            </div>
            
            <div class="flex justify-end">
            <button class="btn" style="background-color: #66aa93; border-color: #66aa93; color: white;">投稿する</button>
            </div>
        </form>
    </div>
</x-app-layout>