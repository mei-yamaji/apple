<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center gap-2">
      <i class="ri-user-follow-line text-xl text-orange-600"></i>
      <h2 class="text-xl font-semibold leading-tight text-orange-900">
        {{ $user->name }}さんがフォロー中
      </h2>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-5xl mx-auto space-y-6 mt-6 px-4">
      
     @forelse ($followings as $following)
  <div class="flex items-center justify-between bg-orange-50 p-4 rounded-xl shadow hover:bg-orange-100 transition-all duration-200 w-full">
    <div class="flex items-center gap-4">
      @if ($following->profile_image)
        <img src="{{ asset('storage/' . $following->profile_image) }}" alt="プロフィール画像" class="w-8 h-8 rounded-full object-cover" />
      @else
        <i class="ri-user-3-line text-orange-400 text-2xl"></i>
      @endif

      <a href="{{ route('user.show', $following->id) }}" class="text-lg font-semibold text-gray-700 hover:underline">
        {{ $following->name }}
      </a>
    </div>

    <a href="{{ route('user.show', $following->id) }}" class="text-xs text-white bg-orange-400 px-3 py-1 rounded-full shadow hover:bg-orange-500 transition-all duration-200">
      プロフィールを見る
    </a>
  </div>
@empty

        <p class="text-center text-gray-500 mt-6">フォロー中のユーザーはいません。</p>
      @endforelse

      <div class="mt-6 flex justify-center">
        {{ $followings->links() }}
      </div>

    </div>
  </div>
</x-app-layout>
