<x-admin-layout>
    <form method="GET" action="{{ route('admin.tags.index') }}" class="mb-6 flex items-center space-x-3">
        <input
            type="text"
            name="keyword"
            value="{{ request('keyword') }}"
            placeholder="タグ名で検索"
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
        <h2 class="text-xl font-semibold mb-4">タグ一覧</h2>

        {{-- フラッシュメッセージを削除済み --}}

        <table class="w-full table-auto text-sm text-left text-gray-600">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">タグ名</th>
                    <th class="px-4 py-2">作成日時</th>
                    <th class="px-4 py-2">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tags as $tag)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $tag->id }}</td>
                        <td class="px-4 py-2">{{ $tag->name }}</td>
                        <td class="px-4 py-2">{{ $tag->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.tags.edit', $tag->id) }}" class="text-blue-600 hover:underline">編集</a>
                            <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" class="inline-block" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline ml-2">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $tags->links() }}
        </div>
    </div>
</x-admin-layout>
