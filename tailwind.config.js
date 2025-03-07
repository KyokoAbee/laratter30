import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                warmPeach: '#FFB997',
                softSunshineYellow: '#FFD791',
                mintGreen: '#A7E8BD',
                OffWhite: '#F5F1E'
            },

        },
    },

    plugins: [forms, require('daisyui')],
    daisyui: {
        themes: [
            {
                mytheme: {
                    'primary': '#FFB997', //ピンク
                    'secondary': '#FFD791', //イエロー
                    'accent': '#A7E8BD', //グリーン  
                    'base' : '#F5F1E', //オフホワイト
                    // 他のカスタムカラーここに
                },
            },
        ],
    },
};
