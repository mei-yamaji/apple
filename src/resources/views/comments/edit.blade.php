<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">コメント編集</h2>

            <form method="POST" action="{{ route('comments.update', [$board, $comment]) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1 font-medium">コメント</label>
                    <textarea name="comment" rows="4" class="w-full border rounded px-3 py-2" required>{{ old('comment', $comment->comment) }}</textarea>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    更新する
                </button>
                <a href="{{ route('boards.show', $board) }}" class="ml-4 text-gray-600 hover:underline">戻る</a>
            </form>
        </div>
    </div>
</x-app-layout>