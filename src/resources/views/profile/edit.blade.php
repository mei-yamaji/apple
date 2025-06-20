<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-orange-900 leading-tight">
            {{ __('マイページ編集') }}
        </h2>
    </x-slot>

    {{-- フラッシュメッセージ --}}
    @if (session('status'))
        <div class="flex justify-center mb-6">
            <div class="flex items-center space-x-2 max-w-lg w-full p-3 bg-green-50 text-green-700 rounded-md border border-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-center text-sm font-medium">
                    {{ session('status') }}
                </p>
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-gray-50 shadow sm:rounded-lg">
                @if ($user->profile_image)
                <div class="flex justify-start mb-4">
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="w-32 h-32 rounded-full object-cover">
                </div>
                @endif
                

           <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
           </div>

            <div class="p-4 sm:p-8 bg-gray-50 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-gray-50 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
