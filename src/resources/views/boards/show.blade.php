@php use Illuminate\Support\Facades\Auth; @endphp
<x-app-layout>
  <div class="max-w-5xl mx-auto space-y-6 mt-6 px-4">

    <div class="p-6 border rounded-2xl shadow-lg bg-white dark:bg-gray-800">
      
      <!-- タイトル -->
      <h5 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white mb-4">
        {{ $board->title }}
      </h5>

      <!-- 本文 -->
      <p class="text-gray-700 dark:text-gray-400 break-words mb-6 leading-relaxed">
        {{ $board->description }}
      </p>

      <!-- いいねボタン（右寄せ） -->
      <div class="flex justify-end mt-2">
        <form action="{{ route('likes.store') }}" method="POST">
          @csrf
          <input type="hidden" name="board_id" value="{{ $board->id }}">
          <button type="submit" class="focus:outline-none flex items-center">
            <i class="ri-heart-fill text-2xl {{ $board->likes->contains('user_id', auth()->id()) ? 'text-red-500' : 'text-gray-400 hover:text-red-400' }}"></i>
            <span class="ml-2" style="color: rgb(234, 88, 100);">{{ $board->like_count }}</span>
          </button>
        </form>
      </div>

      <!-- 投稿日・更新日・閲覧数・ユーザー情報 -->
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
            <a href="{{ route('user.show', ['id' => $board->user->id]) }}" class="text-green-500 hover:underline">
              {{ $board->user->name }}
            </a>
          </p>
        </div>

      </div>

      <!-- 戻るボタン -->
      <div class="mt-4">
        <a href="{{ url()->previous() }}" class="text-green-500 hover:underline">戻る</a>
      </div>

    </div>

    <!-- コメントセクション -->
    <div class="p-6 border rounded-2xl shadow-lg bg-white dark:bg-gray-800">
      <h3 class="text-xl font-semibold mb-4">コメント一覧</h3>

      @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-800 rounded">
          {{ session('success') }}
        </div>
      @endif

      @if($board->comments->count() > 0)
        <ul class="mb-6 space-y-4">
          @foreach ($board->comments as $comment)
            <li class="border-b pb-2 flex items-start space-x-3">
              @if ($comment->user && $comment->user->profile_image)
                <img src="{{ asset('storage/' . $comment->user->profile_image) }}"
                     alt="{{ $comment->user->name }}のプロフィール画像"
                     class="w-8 h-8 rounded-full object-cover">
              @else
                <img src="{{ asset('storage/default_icon.png') }}"
                     alt="デフォルトアイコン"
                     class="w-8 h-8 rounded-full object-cover">
              @endif

              <div>
                <div class="flex items-center gap-2">
                  <strong>
                    <a href="{{ route('user.show', $comment->user->id ?? 0) }}" class="text-orange-400 hover:underline">
                      {{ $comment->user?->name ?? '名無し' }}
                    </a>
                  </strong>
                  <span class="text-sm text-gray-500">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <p>{{ $comment->comment }}</p>

                @if (Auth::id() === $comment->user_id)
                  <div class="text-sm text-gray-500 mt-1 flex space-x-2">
                    <a href="{{ route('comments.edit', [$board, $comment]) }}" class="text-green-500 hover:underline">編集</a>
                    <form action="{{ route('comments.destroy', [$board, $comment]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-red-500 hover:underline">削除</button>
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
          <textarea name="comment" rows="4" class="w-full border rounded px-3 py-2" required>{{ old('comment') }}</textarea>
        </div>

        <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">
          コメント投稿
        </button>
      </form>
    </div>

  </div>
</x-app-layout>
