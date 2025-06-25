<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-orange-900 leading-tight">
    コメント編集
    </h2>
  </x-slot>

  <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg my-10">
    <form method="POST" action="{{ route('comments.update', [$board, $comment]) }}">
      @csrf
      @method('PUT')

      <div class="mb-6">
        <label for="comment" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">コメント</label>
        <textarea id="comment" name="comment" rows="6" 
          class="w-full border border-gray-300 rounded-md p-3 text-gray-900 dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-400 transition"
          required>{{ old('comment', $comment->comment) }}</textarea>
      </div>

           <div class="flex justify-between items-center">
            {{-- 戻るボタン --}}
            <a href="{{ route('boards.show', $board->id) }}"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-orange-500 bg-white border border-orange-400 rounded-full shadow hover:bg-orange-100 hover:scale-105 transition-all duration-200">
                <i class="ri-arrow-go-back-line mr-1"></i> 戻る
            </a>

            {{-- 更新するボタン --}}
            <x-primary-button type="submit">
                更新する
            </x-primary-button>
            </div>


    </form>
  </div>
</x-app-layout>
