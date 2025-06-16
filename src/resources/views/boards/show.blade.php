@php use Illuminate\Support\Facades\Auth; @endphp
<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded shadow">
            <h1 class="text-3xl font-bold mb-4">{{ $board->title }}</h1>
            <p class="mb-4">{{ $board->description }}</p>
            <p class="text-sm text-gray-600">閲覧数: {{ $board->view_count }}</p>
            <p class="text-sm text-gray-600">いいね数: {{ $board->like_count }}</p>
            <a href="{{ url()->previous() }}" class="text-green-500 mt-4 inline-block">戻る</a>
        </div>

        {{-- コメントセクション --}}
        <div class="bg-white mt-8 p-6 rounded shadow">
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
                {{-- コメントしたユーザーのプロフィール画像 --}}
                @if ($comment->user && $comment->user->profile_image)
                    <img src="{{ asset('storage/' . $comment->user->profile_image) }}"
                         alt="{{ $comment->user->name }}のプロフィール画像"
                         class="w-8 h-8 rounded-full object-cover">
                @else
                    {{-- デフォルト画像 --}}
                    <img src="{{ asset('storage/default_icon.png') }}"
                         alt="デフォルトアイコン"
                         class="w-8 h-8 rounded-full object-cover">
                @endif

                <div>
                    <div class="flex items-center gap-2">
                        <strong>
                            <a href="{{ route('user.show', $comment->user->id ?? 0) }}" class="text-blue-600 hover:underline">
                                {{ $comment->user?->name ?? '名無し' }}
                            </a>
                        </strong>
                        <span class="text-sm text-gray-500">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <p>{{ $comment->comment }}</p>

                    {{-- 編集・削除ボタン（自分のコメントのみ表示） --}}
                    @if (Auth::id() === $comment->user_id)
                        <div class="text-sm text-gray-500 mt-1 flex space-x-2">
                            <a href="{{ route('comments.edit', [$board, $comment]) }}" class="text-blue-500 hover:underline">編集</a>
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
</x-app-layout>