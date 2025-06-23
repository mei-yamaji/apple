<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-orange-900">
      {{ __('記事作成') }}
    </h2>
  </x-slot>
 
  <div class="w-1/2 mx-auto pt-6 px-8 pb-8">
    @if ($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </div>
    @endif

    <div class="p-8 border rounded-2xl shadow-lg bg-orange-50 dark:bg-gray-800">
 
    <form class="rounded mb-4" method="POST" action="{{ route('boards.preview') }}">
  @csrf

  <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">タイトル</label>
    <input class="shadow border border-slate-300 rounded w-full py-2 px-3" type="text" name="title" value="{{ old('title') }}">
  </div>

  <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">カテゴリー</label>
    <select name="category_id" class="border border-slate-300 rounded w-full py-2 px-3">
      @foreach($categories as $category)
        <option value="{{ $category->id }}" @if(old('category_id') == $category->id) selected @endif>{{ $category->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">タグ（カンマ区切り）</label>
    <input class="shadow border border-slate-300 rounded w-full py-2 px-3" type="text" name="tags" value="{{ old('tags') }}">
    <p class="text-sm text-gray-500">例: Laravel, PHP</p>
  </div>

  <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">本文（Markdown可）</label>
    <textarea id="description" name="description" rows="6" class="shadow border border-slate-300 rounded w-full py-2 px-3">{{ old('description') }}</textarea>
  </div>

  <!-- 画像アップロード -->
  <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">画像アップロード</label>
    <input type="file" id="imageUpload" accept="image/*" />
    <p class="text-sm text-gray-500">アップロード後、自動でMarkdown形式を本文に挿入します</p>
  </div>

  <div class="mb-4">
    <label class="inline-flex items-center">
      <input type="checkbox" name="is_published" value="1" class="form-checkbox text-orange-500" {{ old('is_published') ? 'checked' : '' }}>
      <span class="ml-2 text-gray-700">公開する</span>
    </label>
  </div>

  <div class="flex justify-end">
    <x-primary-button type="submit">
      プレビューを確認
    </x-primary-button>
  </div>
</form>

  </div>
</div>
 
  <!-- JS (画像アップロードとMarkdown挿入) -->
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
</x-app-layout>