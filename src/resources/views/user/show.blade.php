<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-orange-900">
      {{ $user->name }} さんのプロフィール
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-5xl mx-auto space-y-6 mt-6 px-4">

      <!-- プロフィールカード -->
      <div class="flex flex-col items-center bg-white p-6 rounded-2xl shadow-lg">
        @if ($user->profile_image)
          <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" class="w-20 h-20 rounded-full object-cover mb-4">
        @else
          <div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 mb-4">
            No Image
          </div>
        @endif

        <h3 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
          {{ $user->name }}
          @if ($user->is_runteq_student)
            <span class="text-yellow-400">🍎</span>
          @endif
        </h3>

        <p class="mt-2 text-gray-600 text-center">
          {{ $user->bio ?? 'ひとことは未設定です' }}
        </p>

        <form action="{{ route('favorites.toggle', $user->id) }}" method="POST" class="mt-4">
          @csrf
          <button type="submit"
            class="px-6 py-2 text-white text-sm rounded-full shadow hover:scale-105 transition-all duration-200
            {{ auth()->user()->hasFavorited($user->id) ? 'bg-red-500 hover:bg-red-600' : 'bg-orange-400 hover:bg-orange-500' }}">
            @if (auth()->user()->hasFavorited($user->id))
              フォロー解除
            @else
              + フォローする
            @endif
          </button>
        </form>

        @if (session('status'))
          <p class="mt-2 text-green-600 text-sm">{{ session('status') }}</p>
        @endif
        @if (session('error'))
          <p class="mt-2 text-red-600 text-sm">{{ session('error') }}</p>
        @endif
      </div>

      <!-- 投稿記事一覧 -->
       <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="ri-article-line text-orange-400 text-2xl"></i> 投稿記事一覧
            </h3>
      <div>
        @if ($boards->isNotEmpty())
          @foreach ($boards as $board)
            <div class="border rounded-2xl shadow-md p-6 mb-6 bg-white">
              <h2 class="text-2xl font-semibold text-orange-600 hover:underline mb-2">
                <a href="{{ route('boards.show', $board->id) }}">
                  {{ $board->title }}
                </a>
              </h2>
              <div class="text-sm text-gray-500 mb-4">
                投稿者: {{ $board->user->name ?? '不明' }}
                @if (!empty($board->user->is_runteq_student) && $board->user->is_runteq_student)
                  <span>🍎</span>
                @endif
                投稿日: {{ $board->created_at->format('Y/m/d H:i') }}
              </div>
              <div class="prose prose-gray max-w-none">
                @php
                  $htmlWithoutImages = preg_replace('/<img[^>]*>/', '', $board->description_html ?? '');
                  $plainDescription = strip_tags($htmlWithoutImages);
                  $maxLength = 100;
                  $shortDescription = Str::limit($plainDescription, $maxLength);
                @endphp

                {{ $shortDescription }}

                @if (Str::length($plainDescription) > $maxLength)
                  <a href="{{ route('boards.show', $board->id) }}" class="text-orange-500 hover:underline ml-1">続きを読む</a>
                @endif
              </div>

              <div class="mt-4 flex items-center gap-4">
                <span class="text-sm text-gray-600">💖 {{ $board->likes_count ?? 0 }} 件のいいね</span>
              </div>
            </div>
          @endforeach

          <div class="mt-4">{{ $boards->links() }}</div>
        @else
          <p class="text-gray-500 text-center">まだ投稿がありません。</p>
        @endif
      </div>

    </div>
  </div>
</x-app-layout>

