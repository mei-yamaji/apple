<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-orange-900">
            {{ $user->name }} さんのプロフィール
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-8 px-4">
        <!-- プロフィールカード -->
        <div class="bg-white shadow-lg rounded-2xl p-6 text-center">
            {{-- プロフィール画像 --}}
            @if ($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image"
                    class="w-32 h-32 rounded-full object-cover mx-auto mb-4 shadow">
            @else
                <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center mx-auto mb-4 shadow">
                    <span class="text-gray-500">No Image</span>
                </div>
            @endif

            {{-- ユーザー名 --}}
            <p class="text-2xl font-bold text-gray-800">
                {{ $user->name }}
                @if ($user->is_runteq_student)
                    <span class="text-yellow-400">🍎</span>
                @endif
            </p>

            {{-- 一言 --}}
            <p class="text-gray-600 mt-2">{{ $user->bio ?? 'ひとことは未設定です' }}</p>

            {{-- お気に入り登録ボタン --}}
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

            {{-- フラッシュメッセージ --}}
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

            @forelse ($boards as $board)
                <div class="bg-white p-4 mb-4 rounded-lg shadow hover:bg-gray-50 transition">
                    <a href="{{ route('boards.show', $board->id) }}" class="text-lg font-bold text-orange-500 hover:underline">
                        {{ $board->title }}
                    </a>
                    <p class="text-gray-600 mt-2 break-words max-w-full whitespace-normal">{{ Str::limit($board->description, 100) }}</p>
                    <p class="text-sm text-gray-400 mt-1">{{ $board->created_at->format('Y-m-d H:i') }} に投稿</p>
                </div>
            @empty
                <p class="text-gray-500 text-center mt-6">まだ投稿がありません。</p>
            @endforelse

            <!-- ページネーションリンク -->
           <div class="mt-4" style="border:none; padding:0;">
                {{ $boards->links() }}
           </div>
        </div>
    </div>
</x-app-layout>
