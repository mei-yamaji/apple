<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-orange-900 leading-tight">
            投稿の編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('boards.update', $board->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">タイトル</label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="{{ old('title', $board->title) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                        >
                        @error('title')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">本文</label>
                        <textarea
                            name="description"
                            id="description"
                            rows="5"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                        >{{ old('description', $board->description) }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                         <label class="inline-flex items-center">
                            <input
                                 type="checkbox"
                                 name="is_published"
                                 value="1"
                                 class="form-checkbox text-orange-500"
                                {{ old('is_published', $board->is_published) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">公開する</span>
                         </label>
                    </div>


                    <div class="flex items-center justify-between">
                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            更新する
                        </button>

                        <a href="{{ route('boards.show', $board->id) }}" class="text-sm text-gray-600 hover:underline">
                            戻る
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
