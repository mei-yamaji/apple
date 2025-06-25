<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
 
        <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

       <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?v=1') }}">
       
        <title>apple</title>
 
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Comic+Relief&family=IBM+Plex+Sans+JP&display=swap" rel="stylesheet">


 
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')   {{-- CSSの挿入ポイント --}}
    </head>
    <body class="font-kiwirelief">
        <div class="min-h-screen" style="background-color:#FED16A;">
        @if (Auth::check())
            @include('layouts.navigation')
        @else
            @include('layouts.guest-navigation')
        @endif
 
            <!-- Page Heading -->
            @isset($header)
                <header class="shadow" style="background-color:#FFF4A4;">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 ">
                        {{ $header }}
                    </div>
                </header>
            @endisset
 
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @stack('scripts')  {{-- JSの挿入ポイント --}}
    </body>
</html>
 
 