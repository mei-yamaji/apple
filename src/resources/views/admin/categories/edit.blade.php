<x-admin-layout>
<h2 class="text-lg font-bold mb-4">カテゴリ編集</h2>
 
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">

        @csrf

        @method('PUT')
 
        <div class="mb-4">
<label for="name" class="block text-gray-700 text-sm font-bold mb-2">カテゴリ名</label>
<input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"

                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

            @error('name')
<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>

            @enderror
</div>
 
        <div class="flex items-center justify-between">
<button type="submit"

                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">

                更新
</button>
<a href="{{ route('admin.categories.index') }}" class="text-blue-500 hover:underline">一覧に戻る</a>
</div>
</form>
</x-admin-layout>

 