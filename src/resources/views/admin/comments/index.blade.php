<x-admin-layout>
    <div class="overflow-x-auto bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">コメント一覧</h2>
        <table class="w-full table-auto text-sm text-left text-gray-600">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">ユーザー</th>
                    <th class="px-4 py-2">ボード</th>
                    <th class="px-4 py-2">内容</th>
                    <th class="px-4 py-2">作成日時</th>
                    <th class="px-4 py-2">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($comments as $comment)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $comment->id }}</td>
                        <td class="px-4 py-2">{{ $comment->user->name ?? '不明' }}</td>
                        <td class="px-4 py-2">{{ $comment->board->title ?? '不明' }}</td>
                        <td class="px-4 py-2">{{ Str::limit($comment->comment, 50) }}</td>
                        <td class="px-4 py-2">{{ $comment->created_at }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.comments.edit', $comment->id) }}" class="text-blue-600 hover:underline">編集</a>
                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="inline-block" onsubmit="return confirm('本当に削除しますか？');">
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
            {{ $comments->links() }}
        </div>
    </div>
</x-admin-layout>