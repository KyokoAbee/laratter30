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
                OffWhite: '#F5F5DC',
                Green: '#66aa93',
                Red: '#e77965',
                Navy: '#30466f',
                bejyu: '#f1ded6',
            },

        },
    },

    plugins: [forms, require('daisyui')],
    daisyui: {
        themes: ['light', 'dark',
            {
                mytheme: {
                    'primary': '#66aa93', // 緑
                    'secondary': '#e77965', // 赤
                    'base-100': '#FFFFFF',  // 背景色（白）
                    // 'accent': '#30466f', // 紺
                    // 'base-100': '#f1ded6', // 全体
                    // 'info': '#A7E8BD',      // 情報メッセージ（ミントグリーン）
                    // 'success': '#A7E8BD',   // 成功メッセージ
                    // 'warning': '#FFD791',   // 警告メッセージ
                    // 'error': '#FFB997',     // エラーメッセージ（柔らかい表現）
                },
            },
        ],
        defaultTheme: "mytheme",
    },
};
