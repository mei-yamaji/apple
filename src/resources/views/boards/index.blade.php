<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-orange-900">
      {{ __('投稿記事一覧') }}
    </h2>
  </x-slot>

    @if (session('success'))
        <div class="flex justify-center mb-6">
            <div class="flex items-center space-x-2 max-w-lg w-full p-3 bg-green-50 text-green-700 rounded-md border border-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-center text-sm font-medium">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    @endif

  <div class="py-12">
  <div class="max-w-5xl mx-auto space-y-6 mt-6 px-4">
    <!-- 検索フォーム -->
<form action="{{ route('boards.index') }}" method="GET" class="mb-6">
  <div class="flex space-x-2">
    <input type="text" name="keyword" placeholder="キーワードで検索"
           value="{{ request('keyword') }}"
           class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-green-500 focus:border-green-300" />
    <button type="submit"
            class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-orange-400 rounded-full shadow-md hover:bg-orange-500 hover:scale-105 transition-all duration-200 focus:outline-none">
      <i class="ri-search-line text-base"></i>
    </button>
  </div>
</form>

    @foreach ($boards as $board)
      <div class="p-6 border rounded-2xl shadow-lg bg-white dark:bg-gray-800">

        <!-- タイトル -->
        <h5 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white mb-4">
          <a href="{{ route('boards.show', $board->id) }}" class="text-orange-500 hover:underline">
            {{ $board->title }}
          </a>
        </h5>

        <!-- カテゴリー表示 -->
        <div class="mb-2">
          <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
            {{ $board->category->name ?? '未分類' }}
          </span>
        </div>

        <!-- タグ表示 -->
        <div class="flex flex-wrap mb-2">
          @if (!empty($board->tags) && $board->tags->isNotEmpty())
            @foreach ($board->tags as $tag)
              <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold mr-2 mb-2 px-2.5 py-0.5 rounded-full">
                #{{ $tag->name }}
              </span>
            @endforeach
          @endif
        </div>

        <!-- 本文＋いいねボタンを横並びに -->
        @php
  $maxLength = 80;
  $plainDescription = preg_replace('/!\[.*?\]\(.*?\)/', '', $board->description);
  $textOnly = Str::limit(strip_tags($plainDescription), $maxLength, '...');
@endphp

<div class="flex justify-between items-start mb-0">
  <p class="text-gray-700 dark:text-gray-400 break-words leading-relaxed max-w-[calc(100%-4.5rem)] mb-0  text-xl">
    {{ $textOnly }}
    @if (Str::length(strip_tags($plainDescription)) > $maxLength)
      <a href="{{ route('boards.show', $board->id) }}" class="text-orange-500 hover:underline ml-1">続きを読む</a>
    @endif
  </p>

    <!-- いいね機能 -->
  <button class="like-button flex items-center focus:outline-none ml-4" data-board-id="{{ $board->id }}">
    <i class="ri-heart-fill text-xl {{ $board->likes->contains('user_id', auth()->id()) ? 'text-red-500' : 'text-gray-400 hover:text-red-400' }}"></i>
    <span class="ml-2" style="color:rgb(234, 88, 100);">{{ $board->like_count }}</span>
  </button>
  
</div>


        <!-- 投稿日・更新日・いいね数・閲覧数 + ユーザー情報 -->
        <div class="flex items-center mt-0 justify-between text-gray-400 dark:text-gray-400 text-sm mb-0">

          <div class="flex space-x-4">
            <p>投稿日: {{ $board->created_at->format('Y/m/d H:i') }}</p>
            <p>更新日: {{ $board->updated_at->format('Y/m/d H:i') }}</p>
            <p class="text-gray-400">
              <i class="ri-eye-fill"></i> : {{ $board->view_count }}</p>
            <p class="text-gray-400">
              <i class="ri-chat-3-line"></i> : {{ $board->comments_count }}</p>
          </div>

          <!-- 投稿者情報 -->
          <div class="flex items-center">
            @if ($board->user->profile_image)
              <img src="{{ asset('storage/' . $board->user->profile_image) }}"
                   alt="Profile Image"
                   class="w-12 h-12 rounded-full object-cover mr-3">
            @else
              <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                <span class="text-gray-500 text-sm">No Image</span>
              </div>
            @endif

            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
              <a href="{{ route('user.show', ['user' => $board->user->id]) }}" class="text-green-500 hover:underline">
                {{ $board->user->name }}
              </a>
              @if ($board->user->is_runteq_student)
                <span>🍎</span>
              @endif
            </p>
          </div>


        </div>

      </div>
    @endforeach
    <div class="mt-4">
        {{ $boards->links() }}
    </div>
  </div>
</x-app-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
  $('.like-button').on('click', function() {
    var button = $(this);
    var boardId = button.data('board-id');

    $.ajax({
      url: '{{ route("likes.store") }}',
      method: 'POST',
      data: {
        board_id: boardId,
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        const icon = button.find('i');

        if (response.liked) {
          icon.removeClass('text-gray-400 hover:text-red-400').addClass('text-red-500');
        } else {
          icon.removeClass('text-red-500').addClass('text-gray-400 hover:text-red-400');
        }

        // 数字更新
        button.find('span').text(response.likeCount);

        // ❤️ アニメーションを付ける
        icon.removeClass('heart-animate');
        void icon[0].offsetWidth;  // ←これ重要（再アニメ用）
        icon.addClass('heart-animate');
      },
      error: function() {
        alert('通信エラーが発生しました。');
      }
    });
  });
});
</script>
  <style>
  @keyframes heart-pop {
    0% {
      transform: scale(1);
    }
    50% {
      transform: scale(1.4);
    }
    100% {
      transform: scale(1);
    }
  }

  .heart-animate {
    animation: heart-pop 0.3s ease;
  }
  </style>
