import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],

    // Dark variants only apply when a `dark` class is present, so a visitor's
    // OS dark mode no longer turns the white auth cards and inputs dark.
    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    DEFAULT: '#9b1b61',
                    dark: '#7a1550',
                },
            },
        },
    },

    plugins: [forms],
};
