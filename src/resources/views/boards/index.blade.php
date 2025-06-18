<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-orange-900">
      {{ __('投稿記事一覧') }}
    </h2>
  </x-slot>
 
  <div class="max-w-5xl mx-auto space-y-6 mt-6 px-4">
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
      <div class="flex flex-wrap">
    @if (!empty($board->tags) && $board->tags->isNotEmpty())
        <div class="flex flex-wrap">
            @foreach ($board->tags as $tag)
                <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold mr-2 mb-2 px-2.5 py-0.5 rounded-full">
                    #{{ $tag->name }}
                </span>
            @endforeach
        </div>
    @endif
      </div>
 
        <!-- 本文 一定文字数で省略-->
        @php
          $maxLength = 120;
        @endphp
 
        <p class="text-gray-700 dark:text-gray-400 break-words mb-6 leading-relaxed">
          {{ Str::limit($board->description, $maxLength, '...') }}
 
          @if (Str::length($board->description) > $maxLength)
            <a href="{{ route('boards.show', $board->id) }}" class="text-orange-500 hover:underline ml-1">続きを読む</a>
          @endif
        </p>
 
        <!-- いいねボタン（右寄せ） -->
<div class="flex justify-end mt-2">
  <button class="like-button flex items-center focus:outline-none" data-board-id="{{ $board->id }}">
    <i class="ri-heart-fill text-2xl {{ $board->likes->contains('user_id', auth()->id()) ? 'text-red-500' : 'text-gray-400 hover:text-red-400' }}"></i>
    <span class="ml-2" style="color:rgb(234, 88, 100);">{{ $board->like_count }}</span>
  </button>
</div>

 
        <!-- 投稿日・更新日・いいね数・閲覧数 + ユーザー情報 -->
        <div class="flex items-center mt-3 justify-between text-gray-400 dark:text-gray-400 text-sm mb-6">
 
          <div class="flex space-x-4">
            <p>投稿日: {{ $board->created_at->format('Y/m/d H:i') }}</p>
            <p>更新日: {{ $board->updated_at->format('Y/m/d H:i') }}</p>
            <p class="text-gray-500">閲覧数: {{ $board->view_count }}</p>
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
 
            <p class="text-sm text-gray-500 dark:text-gray-400">
               <a href="{{ route('user.show', ['user' => $board->user->id]) }}" class="text-green-500 hover:underline">

                {{ $board->user->name }}
              </a>
            </p>
          </div>
 
        </div>
 
      </div>
    @endforeach
  </div>
</x-app-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
  $('.like-button').on('click', function() {
    var button = $(this);
    var boardId = button.data('board-id');

    $.ajax({
      url: '{{ route("likes.store") }}',  // ルート名を合わせてください
      method: 'POST',
      data: {
        board_id: boardId,
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if (response.liked) {
          button.find('i').removeClass('text-gray-400 hover:text-red-400').addClass('text-red-500');
        } else {
          button.find('i').removeClass('text-red-500').addClass('text-gray-400 hover:text-red-400');
        }
        button.find('span').text(response.likeCount);
      },
      error: function() {
        alert('通信エラーが発生しました。');
      }
    });
  });
});
</script>

 
 