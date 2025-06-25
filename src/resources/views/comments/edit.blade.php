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

             <div class="flex justify-between items-center mt-4">
                <x-primary-button type="submit">
                    更新する
                </x-primary-button>
                <a href="{{ route('boards.show', $board) }}"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-orange-500 border border-orange-400 rounded-full shadow hover:bg-orange-100 hover:scale-105 transition-all duration-200">
                    <i class="ri-arrow-go-back-line mr-1"></i> 戻る
                </a>


             </div>
            </form>
        </div>
    </div>
</x-app-layout>