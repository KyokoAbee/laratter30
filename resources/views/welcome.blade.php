<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', '本の森') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <!-- ロゴ部分 -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/book4.png') }}" class="h-24 w-auto mx-auto mb-6" alt="本の森ロゴ">
        </div>
        
        <!-- ボタン -->
        <div class="mb-6">
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-full text-Navy font-medium text-sm hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    自分のページへ
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-full text-Navy font-medium text-sm hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    ログイン
                </a>
            @endauth
        </div>
        
        <!-- サイト説明 -->
        <div class="max-w-2xl mx-auto text-center text-gray-700 space-y-8">
            <p class="text-lg md:text-xl">
                「HONMIKKE」は、人を起点にした、本との出会いの場です。 
            </p>
            
            <p class="text-lg md:text-xl">
                本を通じて誰かを想ったり、自分のことを想ってくれた誰かを想像しながら、
            </p>

            <p class="text-lg md:text-xl">
            あなたが今読むべき運命的な一冊との巡り合いを楽しんでみてください。
            </p>
        </div>
        
        <!-- 下部リンク -->
        <div class="mt-20">
            <a href="{{ route('register') }}" class="inline-flex items-center text-Navy hover:text-Navy/80 transition-colors text-sm">
                <span>できることを見る</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
</body>
</html>