<x-admin-layout>
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


    <div class="overflow-x-auto bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">投稿一覧</h2>

        <table class="w-full table-auto text-sm text-left text-gray-600">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-2">投稿者</th>
                    <th class="px-6 py-2">タイトル</th>
                    <th class="px-6 py-2 w-48">本文</th>
                    <th class="px-6 py-2 w-36">作成日</th>
                    <th class="px-6 py-2 w-36">更新日</th>
                    <th class="px-6 py-2">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($boards as $board)
                    <tr class="border-b">
                        <td class="px-6 py-2 whitespace-nowrap">{{ $board->user->name }}</td>

                        <td class="px-6 py-2">{{ $board->title }}</td>
                        <td class="px-6 py-2 truncate max-w-xs">{{ Str::limit($board->description, 50) }}</td>

                        <td class="px-6 py-2 whitespace-nowrap w-36">{{ $board->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-2 whitespace-nowrap w-36">{{ $board->updated_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            <a href="{{ route('admin.boards.edit', $board->id) }}"
                               class="text-blue-600 hover:underline text-sm"
                               dusk="edit-board-button">編集</a>
                            <form action="{{ route('admin.boards.destroy', $board->id) }}" method="POST"
                                  class="inline-block ml-2"
                                  onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:underline text-sm"
                                        dusk="delete-board-button">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                @if ($boards->isEmpty())
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            投稿がありません。
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="mt-4">
            {{ $boards->links() }}
        </div>
    </div>
</x-admin-layout>
