<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
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

      <div class="flex justify-between">
        <x-primary-button type="submit">
          編集に戻る
        </x-primary-button>

            <form action="{{ route('boards.show', $board) }}" method="GET">
        <x-primary-button type="submit">
        更新する
        </x-primary-button>
    </form>
    </div>
  </div>
</x-app-layout>
