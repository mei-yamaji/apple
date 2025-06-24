<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
    <div class="z-10 p-8 rounded-lg  max-w-md w-full mt-4">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-yellow-50"/>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-1.5">
            <x-input-label for="email" :value="__('Email')" class="text-yellow-50"/>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-1.5">
            <x-input-label for="password" :value="__('Password')" class="text-yellow-50"/>

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-1.5">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-yellow-50"/>

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-2">
            <label for="runteq_student" class="inline-flex items-center">
                <input id="runteq_student" type="checkbox" name="runteq_student" value="1" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" {{ old('runteq_student') ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-white">あなたはRUNTEQの受講生ですか</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-0">
            <a class="underline text-sm text-yellow-50 hover:text-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </div>
    </form>
</x-guest-layout>
