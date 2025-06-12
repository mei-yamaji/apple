<x-app-layout>
    @if (session('status'))
      <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-800 border border-green-300 text-center">
        {{ session('status') }}
      </div>
    @endif
    <div class="top-wrapper relative">
        <div class="top-inner-text absolute inset-0 flex items-center justify-center text-black">
            <h1 class="text-4xl font-bold">APPLE</h1>
        </div>
    </div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
