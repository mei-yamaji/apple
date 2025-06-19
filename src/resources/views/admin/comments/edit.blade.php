<x-admin-layout>
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">コメント編集</h2>
        <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">内容</label>
                <textarea name="comment" rows="5" class="w-full border border-gray-300 rounded p-2">{{ old('comment', $comment->comment) }}</textarea>
                @error('comment')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">更新</button>
            <a href="{{ route('admin.comments.index') }}" class="ml-2 text-gray-600 hover:underline">戻る</a>
        </form>
    </div>
</x-admin-layout>
