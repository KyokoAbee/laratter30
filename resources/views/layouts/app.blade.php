<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="mytheme">
    <head>
        <!-- メタタグなど -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#f1ded6]">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- ページヘッダー（必要な場合） -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- メインコンテンツ -->
            <main class="flex-grow">
                <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>

            <!-- フッター -->
            <footer class="bg-white py-6 mt-auto">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} HONMIKKE. All rights reserved.
                </div>
            </footer>
        </div>
    </body>
</html>