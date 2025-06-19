<x-admin-layout>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white p-3">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        投稿者
                    </th>
                    <th scope="col" class="px-6 py-3">
                        タイトル
                    </th>
                    <th scope="col" class="px-6 py-3">
                        本文
                    </th>
                    <th scope="col" class="px-6 py-3">
                        作成日
                    </th>
                    <th scope="col" class="px-6 py-3">
                        更新日
                    </th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($boards as $board)
                    <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $board->user->name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $board->title }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $board->description }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $board->created_at }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $board->updated_at }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.boards.edit', $board->id) }}"
                                class="font-medium text-green-600 dark:text-green-500 hover:underline"
                                dusk="edit-board-button">Edit</a>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.boards.destroy', $board->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline btn btn-danger"
                                    dusk="delete-board-button"
                                    onclick="if(!confirm('本当に削除しますか？')){return false}";>Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $boards->links() }}
    </div>
</x-admin-layout>