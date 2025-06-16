{{-- resources/views/mypage.blade.php --}}
<x-app-layout>
<x-slot name="header">
<h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('マイページ') }}
</h2>
</x-slot>
 
    <div class="py-12">
<div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
<p>ようこそ、{{ Auth::user()->name }} さん！</p>
 
                <div class="mt-4">
<a href="{{ route('profile.edit') }}" class="text-blue-500 underline">
                        プロフィールを編集する
</a>
</div>
</div>
</div>

@if($boards->isNotEmpty())
  <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 mt-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
      <h3 class="text-lg font-bold mb-4">投稿一覧</h3>
      @foreach ($boards as $board)
        <div class="border-b border-gray-200 py-4">
          <h4 class="text-xl font-semibold">
            <a href="{{ route('boards.show', $board->id) }}" class="text-orange-500 hover:underline">
              {{ $board->title }}
            </a>
          </h4>
          <p class="text-sm text-gray-500">投稿日：{{ $board->created_at->format('Y/m/d H:i') }}</p>
          <p class="text-gray-700">{{ Str::limit($board->description, 100, '...') }}</p>
        </div>
      @endforeach
    </div>
  </div>
@else
  <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 mt-8 text-gray-500">
    あなたの投稿はまだありません。
  </div>
@endif

</div>
</x-app-layout>