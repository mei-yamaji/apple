<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-orange-900 leading-tight">
            投稿プレビュー
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg my-10">
        <p class="mb-2"><strong>タイトル:</strong> {{ $title }}</p>
        <p class="mb-2"><strong>カテゴリ:</strong> {{ $category->name ?? '-' }}</p>
        <p class="mb-2"><strong>タグ:</strong> {{ implode(', ', $tags) }}</p>
        <p class="mb-6"><strong>公開設定:</strong> {{ $is_published ? '公開' : '非公開' }}</p>

        <div class="prose max-w-none mb-8">
            {!! $htmlDescription !!}
        </div>

    <!-- プレビュー内容など -->

   <div class="flex justify-between">
  {{-- 左側：戻るボタン --}}
  <form action="{{ route('boards.create') }}" method="GET">
    <input type="hidden" name="preview" value="1">
    <button type="submit"
      class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-orange-500 bg-white border border-orange-400 rounded-full shadow hover:bg-orange-100 hover:scale-105 transition-all duration-200">
      <i class="ri-arrow-go-back-line mr-1"></i> 戻る
    </button>
  </form>

      
            <form action="{{ route('boards.store') }}" method="POST">
            @csrf
            <input type="hidden" name="title" value="{{ $title }}">
            <input type="hidden" name="description" value="{{ $description }}">
            <input type="hidden" name="category_id" value="{{ $category->id }}">
            <input type="hidden" name="tags" value="{{ implode(',', $tags) }}">
            <input type="hidden" name="is_published" value="{{ $is_published ? 1 : 0 }}">

            {{-- 右側：投稿するボタン --}}
  {{-- 右側：投稿するボタン --}}
<x-primary-button type="submit">
    投稿する
</x-primary-button>



    </div>
</x-app-layout>


