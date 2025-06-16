<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('投稿記事一覧') }}
    </h2>
  </x-slot>

  <div class="max-w-4xl mx-auto space-y-4 mt-6">
    @foreach ($boards as $board)
      <div class="p-4 border rounded shadow-sm bg-white dark:bg-gray-800">
        <!-- タイトル -->
        <h5 class="mt-3 mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
          <a href="{{ route('boards.show', $board->id) }}" class="text-orange-500 hover:underline">
          {{ $board->title }}
          </a>
        </h5>

        <!-- 本文 -->
        <p class="my-3 font-normal text-gray-700 dark:text-gray-400 break-words">
          {{ $board->description }}
        </p>

        <!-- プロフィールアイコン + ユーザー名 -->
        <div class="flex items-center mt-3">
          @if ($board->user->profile_image)
            <img src="{{ asset('storage/' . $board->user->profile_image) }}"
                 alt="Profile Image"
                 class="w-10 h-10 rounded-full object-cover mr-2">
          @else
            <!-- 画像がない場合のデフォルトアイコン -->
            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center mr-2">
              <span class="text-gray-500 text-sm">No Image</span>
            </div>
          @endif

          <p class="text-sm text-gray-500 dark:text-gray-400">
            by <a href="{{ route('user.show', ['id' => $board->user->id]) }}" class="text-green-500 hover:underline">
            {{ $board->user->name }}
             </a>
          </p>
        </div>
      </div>
    @endforeach
  </div>
</x-app-layout>
