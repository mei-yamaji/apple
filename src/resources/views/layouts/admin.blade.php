<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @hasSection('title')
            <title>RUNTEQ BOARD | @yield('title')</title>
        @else
            <title>APPLE BOARD</title>
        @endif
        <!-- Scripts -->
        @vite(['resources/css/admin.css', 'resources/js/admin.js'])

        <!-- Remix Icon CDN -->
        <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

        <link rel="icon" type="image/x-icon" href="{{ asset('admin-favicon.ico?v=2') }}">


    </head>
    <body class="bg-gray-800 font-sans leading-normal tracking-normal mt-12">
        @include('layouts.admin-navigation')
        <!-- Page Content -->
        <main>
            <div class="flex flex-col md:flex-row">
                @include('layouts.admin-sidebar')
                <section class="w-full h-screen">
                    <div id="main" class="main-content flex-1 mt-2 md:mt-2 pb-24 md:pb-5">
                        <div class="rounded-tl-3xl from-blue-900 p-4 text-2xl">
                            {{ $slot }}
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </body>
</html>