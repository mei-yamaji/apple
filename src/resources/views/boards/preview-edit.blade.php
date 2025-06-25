<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        投稿編集プレビュー
    </h2>
  </x-slot>

  <div class="px-4">
    <div class="bg-white dark:bg-gray-800 max-w-3xl mx-auto p-8 rounded-xl shadow-lg my-10">

      <p class="mb-2"><strong>タイトル:</strong> {{ $input['title'] }}</p>
      <p class="mb-2"><strong>カテゴリ:</strong> {{ $category->name ?? '-' }}</p>
      <p class="mb-2">
        <strong>タグ:</strong> 
        @if(is_array($input['tags']))
          {{ implode(', ', $input['tags']) }}
        @else
          {{ $input['tags'] }}
        @endif
      </p>
      <p class="mb-6"><strong>公開設定:</strong> {{ ($input['is_published'] ?? 0) ? '公開' : '非公開' }}</p>

      <div class="prose max-w-none mb-8">
        {!! \Illuminate\Support\Str::markdown($input['description']) !!}
      </div>

      <form method="POST" action="{{ route('boards.update', $board->id) }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="title" value="{{ $input['title'] }}">
        <input type="hidden" name="description" value="{{ $input['description'] }}">
        <input type="hidden" name="tags" value="{{ is_array($input['tags']) ? implode(',', $input['tags']) : $input['tags'] }}">
        <input type="hidden" name="category_id" value="{{ $input['category_id'] }}">
        <input type="hidden" name="is_published" value="{{ $input['is_published'] ?? 0 }}">

        <div class="flex justify-between">
          {{-- 戻って修正ボタン --}}
          <form action="{{ route('boards.edit', $board->id) }}" method="GET">
            <x-primary-button type="submit">
         編集に戻る
            </x-primary-button>
          </form>

          {{-- この内容で更新ボタン --}}
          <x-primary-button type="submit">
            この内容で更新
          </x-primary-button>
        </div>
      </form>

    </div>
  </div>
</x-app-layout>
