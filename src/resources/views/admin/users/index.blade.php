<x-admin-layout>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white p-3">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        名前
                    </th>
                    <th scope="col" class="px-6 py-3">
                        メールアドレス
                    </th>
                    <th scope="col" class="px-6 py-3">
                        作成日時
                    </th>
                    <th scope="col" class="px-6 py-3">
                        更新日時
                    </th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $user->id }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $user->created_at }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $user->updated_at }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                dusk="edit-user-button">Edit</a>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                    dusk="delete-user-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</x-admin-layout>