@php use Illuminate\Support\Facades\Auth; @endphp
<x-app-layout>
  <div class="max-w-4xl mx-auto space-y-6 mt-6 px-4 pb-12">

    <!-- 戻るボタン -->
    <div class="mt-4">
      <a href="{{ route('boards.index') }}" 
        class="inline-flex items-center gap-2 px-5 py-3 text-xs font-semibold text-orange-500 border border-orange-400 rounded-full shadow bg-orange-100 hover:scale-105 transition-all duration-200">
        一覧へ戻る
      </a>
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
    </div>


    <div class="p-6 border rounded-2xl shadow-lg bg-white dark:bg-gray-800">
      
    <div class="flex justify-between items-center mb-6">
        <!-- タイトル -->
        <h5 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ $board->title }}
        </h5>

    <!-- 右側ボタンたち -->
    <div class="flex items-center space-x-2">
      @if (Auth::check() && Auth::id() === $board->user_id)
        <a href="{{ route('boards.edit', $board->id) }}" 
          class="bg-blue-100 text-blue-600 rounded-full p-2 hover:bg-blue-200" 
          title="編集">
          <i class="ri-pencil-line text-lg"></i>
        </a>
        <form method="POST" action="{{ route('boards.destroy', $board->id) }}" onsubmit="return confirm('本当に削除しますか？');">
          @csrf
          @method('DELETE')
          <button type="submit" 
                  class="bg-red-100 text-red-600 rounded-full p-2 hover:bg-red-200" 
                  title="削除">
            <i class="ri-delete-bin-6-line text-lg"></i>
          </button>
        </form>
      @endif
 

        <!-- いいねボタン -->
        <div>
            <button type="button"
                    class="like-button focus:outline-none flex items-center"
                    data-board-id="{{ $board->id }}">
                <i class="ri-heart-fill text-4xl {{ $board->likes->contains('user_id', auth()->id()) ? 'text-red-500' : 'text-gray-400 hover:text-red-400' }}"></i>
                <span class="ml-3 text-2xl" style="color: rgb(234, 88, 100);">{{ $board->like_count }}</span>
            </button>
        </div>
    </div>
</div>

    <!-- カテゴリー表示 -->
    <div class="mb-2">
        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
            {{ $board->category->name ?? '未分類' }}
        </span>
    </div>

    <!-- タグ表示 -->
    <div class="mb-8 flex-wrap">
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

    <!-- 本文 -->
    <div class="prose prose-slate max-w-none dark:prose-invert break-words leading-relaxed
                prose-img:w-[700px] prose-img:h-auto prose-img:mx-auto prose-img:rounded mb-6">
        {!! \Illuminate\Support\Str::markdown($board->description) !!}
    </div>

      <!-- 投稿日・更新日・閲覧数・ユーザー情報 -->
      <div class="flex items-center mt-3 justify-between text-gray-400 dark:text-gray-400 text-sm mb-6">

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

          <p class="text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('user.show', ['user' => $board->user->id]) }}" class="font-bold text-green-500 hover:underline">              
              {{ $board->user->name }}
            </a>
            @if ($board->user->is_runteq_student)
              <span>🍎</span>
            @endif
          </p>
        </div>
      </div>
    </div>

    <!-- コメントセクション -->
    <div class="p-6 border rounded-2xl shadow-lg bg-white dark:bg-gray-800">
      <h3 class="text-xl font-semibold mb-4">コメント一覧</h3>

      @if($board->comments->count() > 0)
        <ul class="mb-6 space-y-4">
          @foreach ($board->comments as $comment)
            <li class="border-b pb-2">
  <div class="flex justify-between items-start">
    {{-- 左側：プロフィール画像 + コメント --}}
    <div class="flex space-x-3">
      {{-- プロフィール画像 --}}
      @if ($comment->user && $comment->user->profile_image)
        <img src="{{ asset('storage/' . $comment->user->profile_image) }}"
             alt="{{ $comment->user->name }}のプロフィール画像"
             class="w-8 h-8 rounded-full object-cover">
      @else
        <img src="{{ asset('storage/default_icon.png') }}"
             alt="デフォルトアイコン"
             class="w-8 h-8 rounded-full object-cover">
      @endif

      {{-- コメント内容 --}}
      <div>
        <div class="flex items-center gap-2">
          <strong class="flex items-center">
            <a href="{{ route('user.show', $comment->user->id ?? 0) }}" class="text-orange-400 hover:underline">
              {{ $comment->user?->name ?? '名無し' }}
            </a>
            @if ($comment->user?->is_runteq_student)
              <span>🍎</span>
            @endif
          </strong>
          <span class="text-sm text-gray-500">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
        </div>
        <p>{!! $comment->formatted_body !!}</p>
      </div>
    </div>

    {{-- 右側：編集・削除ボタン --}}
    @if (Auth::id() === $comment->user_id)
  <div class="flex items-center space-x-2">
    {{-- 編集 --}}
    <a href="{{ route('comments.edit', [$board, $comment]) }}" 
       class="bg-blue-100 text-blue-600 rounded-full p-2 hover:bg-blue-200" 
       title="編集">
      <i class="ri-pencil-line text-lg"></i>
    </a>

    {{-- 削除 --}}
    <form action="{{ route('comments.destroy', [$board, $comment]) }}" 
          method="POST" 
          onsubmit="return confirm('本当に削除しますか？');">
      @csrf
      @method('DELETE')
      <button type="submit" 
              class="bg-red-100 text-red-600 rounded-full p-2 hover:bg-red-200" 
              title="削除">
        <i class="ri-delete-bin-6-line text-lg"></i>
      </button>
    </form>
  </div>
@endif
  </div>
</li>
          @endforeach
        </ul>
      @else
        <p class="text-gray-600">まだコメントはありません。</p>
      @endif

      <h3 class="text-xl font-semibold mt-6 mb-2">コメント投稿</h3>

      @if ($errors->any())
        <div class="mb-4 text-red-600">
          <ul>
            @foreach ($errors->all() as $error)
              <li class="text-sm">{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('comments.store', $board) }}">
        @csrf

        <div class="mb-4">
          <label class="block mb-1 font-medium">コメント</label>
          <textarea name="comment" rows="4" class="w-full border rounded px-3 py-2 focus:ring-green-500 focus:border-green-500" required>{{ old('comment') }}</textarea>
        </div>

        <x-primary-button type="submit">
          コメント投稿
        </x-primary-button>
      </form>
    </div>

  </div>
      <style>
    .mention {
      color:green; 
      font-weight: bold;
    }
    </style>

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


