<x-admin-layout>
    <div class="overflow-x-auto bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">投稿一覧</h2>

        {{-- フィルターボタン --}}
        <div class="flex space-x-4 mb-6">
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

        {{-- 投稿一覧テーブル --}}
        <table class="w-full table-auto text-sm text-left text-gray-600">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-2">投稿者</th>
                    <th class="px-6 py-2">タイトル</th>
                    <th class="px-6 py-2">本文</th>
                    <th class="px-6 py-2">公開状態</th>
                    <th class="px-6 py-2">作成日</th>
                    <th class="px-6 py-2">更新日</th>
                    <th class="px-6 py-2">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($boards as $board)
                    <tr class="border-b">
                        {{-- 投稿者情報（横幅制限と改行防止） --}}
                        <td class="px-6 py-2 whitespace-nowrap max-w-[150px] overflow-hidden text-ellipsis">
                            {{ $board->user->name }}
                        </td>

                        <td class="px-6 py-2 whitespace-nowrap max-w-[200px] overflow-hidden text-ellipsis">
                            {{ $board->title }}
                        </td>

                        <td class="px-6 py-2 max-w-[250px] overflow-hidden text-ellipsis">
                            {{ Str::limit($board->description, 50) }}
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            @if ($board->is_published)
                                <span class="text-green-600 font-semibold">公開中</span>
                            @else
                                <span class="text-red-600 font-semibold">非公開</span>
                            @endif
                        </td>
                        <td class="px-6 py-2">{{ $board->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-2">{{ $board->updated_at->format('Y-m-d H:i') }}</td>
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
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            投稿がありません。
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        {{-- ページネーション（フィルター保持付き） --}}
        <div class="mt-4">
            {{ $boards->appends(request()->query())->links() }}
        </div>
    </div>
</x-admin-layout>
