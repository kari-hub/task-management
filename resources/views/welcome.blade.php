<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Task Management System') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white">
        <div class="min-h-screen flex flex-col justify-center items-center p-6">
            <div class="max-w-md w-full text-center">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Task Management System
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Efficiently manage and track your tasks
                    </p>
                </div>

                <div class="space-y-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="inline-block w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Go to Dashboard
                        </a>
                    @else
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" 
                               class="inline-block w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Log in
                        </a>

                        @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="inline-block w-full px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                                Register
                            </a>
                        @endif
                        </div>
                    @endauth
                </div>

                <div class="mt-8 text-sm text-gray-500 dark:text-gray-400">
                    <p>Welcome to your task management solution</p>
                </div>
            </div>
        </div>
    </body>
</html>
