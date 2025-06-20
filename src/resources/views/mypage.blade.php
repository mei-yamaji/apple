<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-orange-900">
      {{ __('マイページ') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-5xl mx-auto space-y-6 mt-6 px-4">

      <!-- プロフィール表示エリア -->
  <div class="flex items-center space-x-6 bg-white p-6 rounded-2xl shadow-lg">

  {{-- プロフィール画像 --}}
  @if (Auth::user()->profile_image)
    <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="プロフィール画像" class="w-20 h-20 rounded-full object-cover ml-4">
  @else
    <div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 ">
      No Image
    </div>
  @endif

  {{-- 名前・ひとこと・メールアドレス --}}
  <div class="flex-1 pl-4">
    {{-- 名前 --}}
    <div class="flex items-center">
      <i class="ri-user-line text-orange-500 text-3xl "></i>
      <h3 class="text-2xl font-semibold text-gray-800">
        {{ Auth::user()->name }}
        @if (Auth::user()->is_runteq_student)
          <span class="text-yellow-400">🍎</span>
        @endif
      </h3>
    </div>

    {{-- ひとこと --}}
    @if (Auth::user()->bio)
      <p class="mt-2 text-gray-600 flex items-center">
        <i class="ri-chat-smile-2-line mr-2 text-orange-500 text-3xl"></i>
        {{ Auth::user()->bio }}
      </p>
    @else
      <p class="mt-2 text-gray-400 italic">ひとことが設定されていません</p>
    @endif

    {{-- メールアドレス --}}
    <p class="mt-2 text-gray-600 flex items-center">
      <i class="ri-mail-line mr-2 text-orange-500 text-3xl"></i>
      {{ Auth::user()->email }}
    </p>
  </div>

  {{-- プロフィール編集ボタン --}}
    <div>
      <x-primary-button>
      <a href="{{ route('profile.edit') }}">
        プロフィール編集
      </a>
      </x-primary-button>
    </div>
  </div>

    {{-- 切り替えボタン --}}
<div class="tabs flex justify-center gap-6 mb-6">
     <span class="text-5xl">🍎</span>
     <span class="text-5xl">🍎</span>
    <a href="{{ route('mypage', ['view' => 'own']) }}">
        <x-primary-button class="text-xl px-12 py-4 {{ $viewMode === 'own' ? 'bg-blue-600 text-white' : '' }}">
            自分の投稿
        </x-primary-button>
    </a>

    <a href="{{ route('mypage', ['view' => 'likes']) }}">
        <x-primary-button class="text-xl px-12 py-4 {{ $viewMode === 'likes' ? 'bg-blue-600 text-white' : '' }}">
            いいねした記事
        </x-primary-button>
    </a>
      <span class="text-5xl">🍎</span>
      <span class="text-5xl">🍎</span>
</div>

   {{-- 投稿一覧表示 --}}
      @if ($viewMode === 'own')
        {{-- 自分の投稿 --}}
        @if ($boards->isNotEmpty())
          @foreach ($boards as $board)
            <div class="border rounded-2xl shadow-md p-6 mb-6 bg-white">
              <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ $board->title }}</h2>
              <div class="text-sm text-gray-500 mb-4">
                投稿者: {{ $board->user->name ?? '不明' }}
                投稿日: {{ $board->created_at->format('Y/m/d H:i') }}
              </div>
              <div class="prose prose-gray max-w-none">
              @php
                // 画像だけ除去
                $htmlWithoutImages = preg_replace('/<img[^>]*>/', '', $board->description_html ?? '');

                // プレーンテキストに変換（タグ除去）
                $plainDescription = strip_tags($htmlWithoutImages);

                // 表示する最大文字数
                $maxLength = 100;

                // 短縮された本文（必要なら）
                $shortDescription = Str::limit($plainDescription, $maxLength);
              @endphp

              {{ $shortDescription }}

              @if (Str::length($plainDescription) > $maxLength)
                <a href="{{ route('boards.show', $board->id) }}" class="text-orange-500 hover:underline ml-1">続きを読む</a>
              @endif
            </div>

              <div class="mt-4 flex items-center gap-4">
                <span class="text-sm text-gray-600">💖 {{ $board->likes_count ?? 0 }} 件のいいね</span>
                @if (route('boards.show', $board->id, false))
                  <a href="{{ route('boards.show', $board->id) }}" class="text-green-600 hover:underline text-sm">詳細を見る</a>
                @endif
              </div>
            </div>
          @endforeach
          <div class="mt-4">{{ $boards->links() }}</div>
        @else
          <p class="text-gray-500 text-center">あなたの投稿はまだありません。</p>
        @endif

      @elseif ($viewMode === 'likes')
        {{-- お気に入り --}}
        @if ($likedBoards->isNotEmpty())
          @foreach ($likedBoards as $board)
            <div class="border rounded-2xl shadow-md p-6 mb-6 bg-white">
              <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ $board->title }}</h2>
              <div class="text-sm text-gray-500 mb-4">
                投稿者: {{ $board->user->name ?? '不明' }}
                投稿日: {{ $board->created_at->format('Y/m/d H:i') }}
              </div>
              <div class="prose prose-gray max-w-none">
              @php
                // 画像だけ除去
                $htmlWithoutImages = preg_replace('/<img[^>]*>/', '', $board->description_html ?? '');

                // プレーンテキストに変換（タグ除去）
                $plainDescription = strip_tags($htmlWithoutImages);

                // 表示する最大文字数
                $maxLength = 100;

                // 短縮された本文（必要なら）
                $shortDescription = Str::limit($plainDescription, $maxLength);
              @endphp

              {{ $shortDescription }}

              @if (Str::length($plainDescription) > $maxLength)
                <a href="{{ route('boards.show', $board->id) }}" class="text-orange-500 hover:underline ml-1">続きを読む</a>
              @endif
            </div>

              <div class="mt-4 flex items-center gap-4">
                <span class="text-sm text-gray-600">💖 {{ $board->likes_count ?? 0 }} 件のいいね</span>
                @if (route('boards.show', $board->id, false))
                  <a href="{{ route('boards.show', $board->id) }}" class="text-green-600 hover:underline text-sm">詳細を見る</a>
                @endif
              </div>
            </div>
          @endforeach
        @else
          <p class="text-gray-500 text-center">お気に入りの投稿はまだありません。</p>
        @endif
      @endif

    </div>
  </div>
</x-app-layout>
