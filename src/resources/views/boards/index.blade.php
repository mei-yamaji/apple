<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('投稿記事一覧') }}
    </h2>
  </x-slot>

  <div class="max-w-4xl mx-auto space-y-4 mt-6">  <!-- 投稿ならべてるやつ -->
    @foreach ($boards as $board)
      <div class="p-4 border rounded shadow-sm bg-white dark:bg-gray-800">
        <h5 class="mt-3 mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
          {{ $board->title }}
        </h5>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          by {{ $board->user->name }}
        </p>
        <p class="my-3 font-normal text-gray-700 dark:text-gray-400 break-words">
          {{ $board->description }}
        </p>
      </div>
    @endforeach
  </div>
</x-app-layout>