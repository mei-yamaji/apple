<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-orange-900">
      {{ $user->name }} さんのプロフィール
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-5xl mx-auto space-y-6 mt-6 px-4">

<div class="flex items-center bg-white p-6 rounded-2xl shadow-lg max-w-6xl mx-auto">

  {{-- プロフィール画像 --}}
  @if ($user->profile_image)
    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" class="w-20 h-20 rounded-full object-cover ml-4">
  @else
    <div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 ml-4">
      No Image
    </div>
  @endif

  {{-- 中央：プロフィール情報 --}}
  <div class="flex-1 pl-6">
    {{-- 名前・フォロー系 --}}
    <div class="flex items-center mb-2">
      <i class="ri-user-line text-orange-500 text-2xl"></i>
      <h3 class="text-2xl font-semibold text-orange-800 ml-2 flex items-center gap-2">
        {{ $user->name }}
        @if ($user->is_runteq_student)
          <span class="text-yellow-400">🍎</span>
        @endif
      </h3>

      {{-- フォロー・フォロワーリンク（名前の右横） --}}
      <div class="flex space-x-3 ml-6">
        <a href="{{ route('profile.followings', $user->id) }}" 
          class="flex items-center gap-1 px-3 py-1 text-xs bg-orange-100 text-orange-600 rounded-full shadow hover:bg-orange-200 hover:scale-105 transition-all duration-200">
            <i class="ri-user-follow-line text-base"></i>
            フォロー中：{{ $user->followings()->count() }}人
        </a>
        <a href="{{ route('profile.followers', $user->id) }}" 
          class="flex items-center gap-1 px-3 py-1 text-xs bg-orange-100 text-orange-600 rounded-full shadow hover:bg-orange-200 hover:scale-105 transition-all duration-200">
            <i class="ri-group-line text-base"></i>
            フォロワー：{{ $user->followers()->count() }}人
        </a>
      </div>
    </div>

    {{-- ひとこと --}}
    @if ($user->bio)
      <p class="mt-1 text-gray-600 flex items-center">
        <i class="ri-chat-smile-2-line mr-2 text-orange-500 text-xl"></i>
        {{ $user->bio }}
      </p>
    @else
      <p class="mt-1 text-gray-400 italic">ひとことが設定されていません</p>
    @endif

    {{-- メールアドレス（自分だけ表示） --}}
    @if (auth()->id() === $user->id)
      <p class="mt-2 text-gray-600 flex items-center">
        <i class="ri-mail-line mr-2 text-orange-500 text-xl"></i>
        {{ $user->email }}
      </p>
    @endif

    {{-- 登録日・投稿数・最終投稿：横並びでかわいく --}}
    <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-700">
      <div class="flex items-center gap-2 px-3 py-2 bg-orange-50 text-orange-700 rounded-xl shadow">
        <i class="ri-calendar-line text-orange-400 text-lg"></i>
        <span>登録日：{{ $user->created_at->format('Y年n月d日') }}</span>
      </div>
      <div class="flex items-center gap-2 px-3 py-2 bg-orange-50 text-orange-700 rounded-xl shadow">
        <i class="ri-article-line text-orange-400 text-lg"></i>
        <span>投稿数：{{ $user->boards()->count() }} 件</span>
      </div>
      <div class="flex items-center gap-2 px-3 py-2 bg-orange-50 text-orange-700 rounded-xl shadow">
        <i class="ri-time-line text-orange-400 text-lg"></i>
        <span>
          最終投稿：
          @if ($user->boards()->exists())
            {{ $user->boards()->latest()->first()->created_at->format('Y年n月d日 H:i') }}
          @else
            投稿なし
          @endif
        </span>
      </div>
    </div>
  </div>

  {{-- 右側：フォローボタン --}}
  @if (auth()->check() && auth()->id() !== $user->id)
    <div class="flex flex-col items-center justify-center space-y-2 ml-6 w-48">
      <form action="{{ route('favorites.toggle', $user->id) }}" method="POST" class="w-full">
        @csrf
        <button type="submit"
          class="w-full px-6 py-2 text-white text-sm rounded-full shadow hover:scale-105 transition-all duration-200
          {{ auth()->user()->hasFavorited($user->id) ? 'bg-red-500 hover:bg-red-600' : 'bg-orange-400 hover:bg-orange-500' }}">
          {{ auth()->user()->hasFavorited($user->id) ? 'フォロー解除' : '+ フォローする' }}
        </button>
      </form>

      {{-- フラッシュメッセージ --}}
      <div class="h-5">
        @if (session('status'))
          <p class="text-green-600 text-sm text-center">{{ session('status') }}</p>
        @elseif (session('error'))
          <p class="text-red-600 text-sm text-center">{{ session('error') }}</p>
        @else
          <span class="invisible">&nbsp;</span>
        @endif
      </div>
    </div>
  @endif

</div>




      <!-- 投稿記事一覧 --> 
    <div class="max-w-5xl mx-auto mt-8 px-4 ">
      <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
        <i class="ri-article-line text-orange-400 text-2xl"></i> 投稿記事一覧
      </h3>
    </div>

      <div>
        @if ($boards->isNotEmpty())
          @foreach ($boards as $board)
            <div class="border rounded-2xl shadow-md p-6 mb-6 bg-white">

              {{-- 📌 ピン留め中なら表示 --}}
              @if ($board->is_pinned)
                <div class="text-sm text-yellow-600 font-semibold mb-2 flex items-center gap-1">
                  <span class="text-lg">📌</span> ピン留め中の投稿
                </div>
              @endif

              {{-- 投稿タイトル --}}
              <h2 class="text-2xl font-semibold text-orange-600 hover:underline mb-2">
                <a href="{{ route('boards.show', $board->id) }}">
                  {{ $board->title }}
                </a>
              </h2>

              {{-- 投稿情報 --}}
              <div class="text-sm text-gray-500 mb-4">
                投稿者: {{ $board->user->name ?? '不明' }}
                @if (!empty($board->user->is_runteq_student) && $board->user->is_runteq_student)
                  <span>🍎</span>
                @endif
                投稿日: {{ $board->created_at->format('Y/m/d H:i') }}
              </div>

              {{-- 本文（100文字以内 & 「続きを読む」リンク） --}}
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

              {{-- いいね数・ピン切り替え --}}
              <div class="mt-4 flex items-center gap-4">
                <span class="text-sm text-gray-600">💖 {{ $board->likes_count ?? 0 }} 件のいいね</span>

                {{-- 🔁 自分のプロフィールだけピン留めボタン表示 --}}
                @if (Auth::id() === $user->id)
                  <form method="POST" action="{{ route('boards.togglePin', $board->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="text-xs px-3 py-1 rounded border transition
                                  {{ $board->is_pinned ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' }}">
                      {{ $board->is_pinned ? 'ピンを外す' : '📌 ピン留めする' }}
                    </button>
                  </form>
                @endif
              </div>
            </div>
          @endforeach

          {{-- ページネーション --}}
          <div class="mt-4">{{ $boards->links() }}</div>

        @else
          <p class="text-gray-500 text-center">まだ投稿がありません。</p>
        @endif
      </div>
    </div>


    </div>
</x-app-layout>

