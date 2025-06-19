<x-app-layout>
<x-slot name="header">
<h2 class="text-xl font-semibold leading-tight text-gray-800">

      {{ $user->name }} さんのプロフィール
</h2>
</x-slot>
 
  <div class="max-w-xl mx-auto mt-6 p-4 bg-white shadow rounded">

    @if ($user->profile_image)
    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="w-32 h-32 rounded-full object-cover mx-auto mb-4">

    @else
    <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center mx-auto mb-4">
      <span class="text-gray-500">No Image</span>
    </div>
    @endif
 
    <p class="text-lg font-semibold text-center">
        {{ $user->name }}
        @if ($user->is_runteq_student)
                <span>🍎</span>
        @endif
    </p>

    <p class="text-gray-600 text-center mt-2">{{ $user->bio }}</p>
</div>
</x-app-layout>
 