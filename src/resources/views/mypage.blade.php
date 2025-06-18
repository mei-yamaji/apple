<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('マイページ') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-5xl mx-auto space-y-6 mt-6 px-4">

      <!-- プロフィール表示エリア -->
<div class="flex items-center space-x-6 bg-white p-6 rounded-2xl shadow-lg">

  {{-- プロフィール画像 --}}
  @if (Auth::user()->profile_image)
    <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="プロフィール画像" class="w-20 h-20 rounded-full object-cover ml-4">
  @else
    <div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 ">
      No Image
    </div>
  @endif

  {{-- 名前・ひとこと・メールアドレス --}}
  <div class="flex-1 pl-4">
    {{-- 名前 --}}
    <div class="flex items-center">
      <i class="ri-user-line text-orange-500 text-3xl "></i>
      <h3 class="text-2xl font-semibold text-gray-800">{{ Auth::user()->name }}</h3>
    </div>

    {{-- ひとこと --}}
    @if (Auth::user()->bio)
      <p class="mt-2 text-gray-600 flex items-center">
        <i class="ri-chat-smile-2-line mr-2 text-orange-500 text-3xl"></i>
        {{ Auth::user()->bio }}
      </p>
    @else
      <p class="mt-2 text-gray-400 italic">ひとことが設定されていません</p>
    @endif

    {{-- メールアドレス --}}
    <p class="mt-2 text-gray-600 flex items-center">
      <i class="ri-mail-line mr-2 text-orange-500 text-3xl"></i>
      {{ Auth::user()->email }}
    </p>
  </div>

  {{-- プロフィール編集ボタン --}}
  <div>
    <a href="{{ route('profile.edit') }}"
       class="inline-block bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
      プロフィール編集
    </a>
  </div>
</div>


      {{-- 投稿一覧 --}}
      @if ($boards->isNotEmpty())
        <div class="space-y-6 mt-8">
          @foreach ($boards as $board)
            <div class="p-6 border rounded-2xl shadow-lg bg-white dark:bg-gray-800">
              <h5 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white mb-4">
                <a href="{{ route('boards.show', $board->id) }}" class="text-orange-500 hover:underline">
                  {{ $board->title }}
                </a>
              </h5>

              <div class="prose prose-slate max-w-none dark:prose-invert break-words mb-6 leading-relaxed
            prose-img:w-64 prose-img:h-auto prose-img:mx-auto prose-img:rounded">
             {!! \Illuminate\Support\Str::markdown($board->description) !!}
              </div>


              <div class="flex items-center mt-3 justify-between text-gray-400 dark:text-gray-400 text-sm mb-6">
                <div class="flex space-x-4">
                  <p>投稿日: {{ $board->created_at->format('Y/m/d H:i') }}</p>
                  <p>更新日: {{ $board->updated_at->format('Y/m/d H:i') }}</p>
                  <p class="text-gray-500">閲覧数: {{ $board->view_count }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-gray-500 mt-8">
          あなたの投稿はまだありません。
        </div>
      @endif

    </div>
  </div>
</x-app-layout>
