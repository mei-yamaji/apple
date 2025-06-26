<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-orange-900 leading-tight">
            投稿の編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-1/2 mx-auto pt-6 px-8 pb-8">
                <form action="{{ route('boards.update', $board->id) }}">
                    @csrf
                    @method('POST')

                <div class="p-8 border rounded-2xl shadow-lg bg-orange-50 dark:bg-gray-800">

                    {{-- タイトル --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">タイトル</label>
                      <input class="shadow border border-slate-300 rounded w-full py-2 px-3 focus:ring-green-500 focus:border-green-500"
                               type="text" name="title" value="{{ old('title', $board->title) }}">
                        @error('title')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- カテゴリー --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">カテゴリー</label>
                        <select name="category_id" class="border border-slate-300 rounded w-full py-2 px-3 focus:ring-green-500 focus:border-green-500">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $board->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- タグ --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">タグ（カンマ区切り）</label>
                        <input class="shadow border border-slate-300 rounded w-full py-2 px-3 focus:ring-green-500 focus:border-green-500"
                               type="text" name="tags" value="{{ old('tags', $tags) }}">
                        <p class="text-sm text-gray-500">例: Laravel, PHP</p>
                    </div>

                    {{-- 本文 --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">本文（Markdown可）</label>
                        <textarea id="description" name="description" rows="6"
                                  class="shadow border border-slate-300 rounded w-full py-2 px-3 focus:ring-green-500 focus:border-green-500">{{ old('description', $board->description) }}</textarea>
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
                                 class="form-checkbox text-orange-500 focus:ring-green-500 focus:border-green-500"
                                {{ old('is_published', $board->is_published) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">公開する</span>
                         </label>
                    </div>


                    {{-- 画像アップロード --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">画像アップロード</label>
                        <input type="file" id="imageUpload" accept="image/*" />
                        <p class="text-sm text-gray-500">アップロード後、自動でMarkdown形式を本文に挿入します</p>
                    </div>

                    {{-- ボタン --}}
                     <div class="flex items-center justify-between mt-4">
                    {{-- 戻るボタン --}}
                    <a href="{{ route('boards.show', $board->id) }}"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-orange-500 bg-white border border-orange-400 rounded-full shadow hover:bg-orange-100 hover:scale-105 transition-all duration-200">
                        <i class="ri-arrow-go-back-line mr-1"></i> 戻る
                    </a>


                    {{-- プレビュー用ボタン --}}
                    <x-primary-button type="submit" name="action" value="preview"
                        formaction="{{ route('boards.preview.update', $board->id) }}">
                        プレビューを確認
                    </x-primary-button>
                    </div>

            </form>
         </div>
        </div>
    </div>

    {{-- 画像アップロード用JS --}}
    <script>
        document.getElementById('imageUpload').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("boards.image-upload") }}', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.url) {
                    const textarea = document.getElementById('description');
                    const markdown = `![画像](${data.url})\n`;
                    textarea.value += markdown;
                    alert('画像をアップロードしました');
                    this.value = '';
                } else {
                    alert('画像アップロードに失敗しました');
                }
            })
            .catch(() => alert('通信エラーが発生しました'));
        });
    </script>
</x-layouts.app>
