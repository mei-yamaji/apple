<x-app-layout>
  <div class="max-w-3xl mx-auto space-y-6 px-4 py-8">

    <h2 class="text-2xl font-bold mb-4">プレビュー</h2>

    <div class="p-4 border rounded bg-white">
      <h3 class="text-xl font-semibold mb-2">{{ $input['title'] }}</h3>

      {{-- カテゴリ --}}
      <p class="mb-2"><strong>カテゴリ:</strong> {{ $category->name ?? '-' }}</p>

      {{-- タグ --}}
      <p class="mb-2">
        <strong>タグ:</strong> 
        @if(is_array($input['tags']))
          {{ implode(', ', $input['tags']) }}
        @else
          {{ $input['tags'] }}
        @endif
      </p>

      {{-- 公開設定 --}}
      <p class="mb-6"><strong>公開設定:</strong> {{ ($input['is_published'] ?? 0) ? '公開' : '非公開' }}</p>

      <div class="prose">
        {!! \Illuminate\Support\Str::markdown($input['description']) !!}
      </div>
    </div>

    <form method="POST" action="{{ route('boards.update', $board->id) }}">
      @csrf
      @method('PUT')

      <input type="hidden" name="title" value="{{ $input['title'] }}">
      <input type="hidden" name="description" value="{{ $input['description'] }}">
      <input type="hidden" name="tags" value="{{ is_array($input['tags']) ? implode(',', $input['tags']) : $input['tags'] }}">
      <input type="hidden" name="category_id" value="{{ $input['category_id'] }}">
      <input type="hidden" name="is_published" value="{{ $input['is_published'] ?? 0 }}">

      <div class="flex justify-between mt-6">
        <a href="{{ route('boards.edit', $board->id) }}"
           class="px-4 py-3 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
          戻って修正
        </a>

        <x-primary-button type="submit">
          この内容で更新
        </x-primary-button>
      </div>
    </form>
  </div>
</x-app-layout>


