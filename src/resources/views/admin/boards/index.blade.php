<x-admin-layout>
    {{-- 検索フォーム --}}
    <form method="GET" action="{{ route('admin.boards.index') }}" class="mb-6 flex items-center space-x-3">
        <input
            type="text"
            name="keyword"
            value="{{ request('keyword') }}"
            placeholder="キーワードで検索"
            class="border rounded px-3 h-10"
        />
        <button
            type="submit"
            class="bg-blue-500 text-white rounded px-4 h-10 flex items-center justify-center text-sm"
        >
            検索
        </button>
    </form>

    {{-- 投稿一覧 --}}
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">投稿一覧</h2>

        {{-- フィルター --}}
        <div class="flex space-x-4 mb-6 flex-wrap">
            <a href="{{ route('admin.boards.index') }}"
               class="px-4 py-2 rounded border text-sm font-medium transition 
                   {{ request('status') === null ? 'bg-gray-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                全て
            </a>
            <a href="{{ route('admin.boards.index', ['status' => 'published']) }}"
               class="px-4 py-2 rounded border text-sm font-medium transition 
                   {{ request('status') === 'published' ? 'bg-gray-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                公開記事
            </a>
            <a href="{{ route('admin.boards.index', ['status' => 'unpublished']) }}"
               class="px-4 py-2 rounded border text-sm font-medium transition 
                   {{ request('status') === 'unpublished' ? 'bg-gray-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                非公開記事
            </a>
        </div>

        {{-- テーブル --}}
        <table class="w-full text-sm text-left text-gray-600 border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 max-w-[100px]">投稿者</th>
                    <th class="px-4 py-2 max-w-[180px]">タイトル</th>
                    <th class="px-4 py-2 max-w-[300px]">本文</th>
                    <th class="px-4 py-2 max-w-[100px]">公開状態</th>
                    <th class="px-4 py-2 max-w-[140px]">作成日時</th>
                    <th class="px-4 py-2 max-w-[140px]">更新日時</th>
                    <th class="px-4 py-2 max-w-[120px]">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($boards as $board)
                    <tr class="border-b align-top">
                        <td class="px-4 py-2 break-words max-w-[100px]">{{ $board->user->name }}</td>
                        <td class="px-4 py-2 break-words max-w-[180px]">{{ $board->title }}</td>
                        <td class="px-4 py-2 break-words max-w-[300px]">{{ Str::limit($board->description, 80) }}</td>
                        <td class="px-4 py-2 max-w-[100px]">
                            @if ($board->is_published)
                                <span class="text-green-600 font-semibold">公開中</span>
                            @else
                                <span class="text-red-600 font-semibold">非公開</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 max-w-[140px]">{{ $board->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2 max-w-[140px]">{{ $board->updated_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2 max-w-[120px] whitespace-nowrap">
                            <a href="{{ route('admin.boards.edit', $board->id) }}" class="text-blue-600 hover:underline text-sm">編集</a>
                            <form action="{{ route('admin.boards.destroy', $board->id) }}" method="POST"
                                  class="inline-block ml-2" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm">削除</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">
                            投稿がありません。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- ページネーション --}}
        <div class="mt-4">
            {{ $boards->appends(request()->query())->links() }}
        </div>
    </div>
</x-admin-layout>
