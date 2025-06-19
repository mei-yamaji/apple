<x-admin-layout>
    <div class="overflow-x-auto bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">ユーザー一覧</h2>

        <table class="w-full table-auto text-sm text-left text-gray-600">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-2">ID</th>
                    <th class="px-6 py-2">名前</th>
                    <th class="px-6 py-2">メールアドレス</th>
                    <th class="px-6 py-2">作成日時</th>
                    <th class="px-6 py-2">更新日時</th>
                    <th class="px-6 py-2">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b">
                        <td class="px-6 py-2">{{ $user->id }}</td>
                        <td class="px-6 py-2">{{ $user->name }}</td>
                        <td class="px-6 py-2">{{ $user->email }}</td>
                        <td class="px-6 py-2">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-2">{{ $user->updated_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="text-blue-600 hover:underline text-sm"
                               dusk="edit-user-button">編集</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                  class="inline-block ml-2"
                                  onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:underline text-sm"
                                        dusk="delete-user-button">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                @if ($users->isEmpty())
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            ユーザーが存在しません。
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-admin-layout>
