<x-guest-layout>
<div class="flex justify-center items-center min-h-screen bg-gray-100">
<div class="bg-white rounded-full shadow-lg w-96 h-96 p-6 flex flex-col justify-center">
<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />
 
            <form method="POST" action="{{ route('login') }}" class="space-y-4">

                @csrf
 
                <!-- Email Address -->
<div>
<x-input-label for="email" :value="__('Email')" />
<x-text-input id="email" class="block mt-1 w-full rounded-full" type="email" name="email"

                                  :value="old('email')" required autofocus autocomplete="username" />
<x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>
 
                <!-- Password -->
<div>
<x-input-label for="password" :value="__('Password')" />
<x-text-input id="password" class="block mt-1 w-full rounded-full"

                                  type="password" name="password" required autocomplete="current-password" />
<x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>
 
                <!-- Remember Me -->
<div class="text-xs text-gray-500">
<label for="remember_me" class="inline-flex items-center">
<input id="remember_me" type="checkbox"

                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
<span class="ms-2">{{ __('Remember me') }}</span>
</label>
</div>
 
                <!-- Login Button -->
<div class="flex justify-center">
<x-primary-button class="rounded-full px-6">

                        {{ __('Log in') }}
</x-primary-button>
</div>
</form>
</div>
</div>
</x-guest-layout>
 