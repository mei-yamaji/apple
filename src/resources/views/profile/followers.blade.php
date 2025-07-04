<x-app-layout>
      <x-slot name="header">
        <div class="flex items-center gap-2">
        <i class="ri-group-line text-3xl text-orange-600"></i>
        <h2 class="text-2xl font-bold text-orange-900">
            {{ $user->name }}さんのフォロワー
        </h2>
        </div>
      </x-slot>

       <div class="max-w-3xl mx-auto py-8 px-4">

            @forelse ($followers as $follower)
                <div class="flex items-center justify-between bg-white p-4 mb-4 rounded-xl shadow hover:bg-orange-100 transition-all duration-200">
                    <div class="flex items-center gap-4">
                        @if ($follower->profile_image)
                            <img src="{{ asset('storage/' . $follower->profile_image) }}" alt="プロフィール画像" class="w-8 h-8 rounded-full object-cover" />
                        @else
                            <i class="ri-user-3-line text-orange-400 text-2xl"></i>
                        @endif
                        <a href="{{ route('user.show', $follower->id) }}" class="text-lg font-semibold text-gray-700 hover:underline">
                            {{ $follower->name }}
                        </a>
                    </div>
                    <a href="{{ route('user.show', $follower->id) }}" class="text-xs text-white bg-orange-400 px-3 py-1 rounded-full shadow hover:bg-orange-500 transition-all duration-200">
                        プロフィールを見る
                    </a>
                </div>
            @empty
                <p class="text-center text-gray-500 mt-6">フォロワーはいません。</p>
            @endforelse

        <div class="mt-6 flex justify-center">
            {{ $followers->links() }}
        </div>
    </div>
</x-app-layout>