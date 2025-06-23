<x-admin-layout>
    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-2">タグ編集</h2>

        <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-gray-700 text-sm font-medium mb-2">タグ名</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $tag->name) }}"
                    class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-300 transition"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded focus:outline-none focus:shadow-outline text-sm"
                >
                    <i class="ri-refresh-line"></i> 更新する
                </button>

                <a href="{{ route('admin.tags.index') }}" class="text-gray-600 hover:text-gray-800 text-sm underline">一覧に戻る</a>
            </div>
        </form>
    </div>
</x-admin-layout>
