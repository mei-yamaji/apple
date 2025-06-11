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
</div>
</x-app-layout>