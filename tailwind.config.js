/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                bg: '#0f1117',
                surface: '#1a1d2e',
                surface2: '#252840',
                accent: '#6366f1',
                accent2: '#818cf8',
                success: '#22c55e',
                danger: '#ef4444',
                warning: '#f59e0b',
                text: '#e2e8f0',
                text2: '#94a3b8',
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
