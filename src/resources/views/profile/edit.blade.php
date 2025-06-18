<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-orange-900 leading-tight">
            {{ __('マイページ編集') }}
        </h2>
    </x-slot>


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
