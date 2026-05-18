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
                // Base Colors (warm dark)
                bg: '#0B0E14',
                surface: '#151A25',
                surface2: '#1E2532',
                surface3: '#2A3441',
                // Primary (gradient-ready)
                primary: {
                    50: '#eef2ff',
                    100: '#e0e7ff',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                },
                // Semantic Colors
                success: '#10b981',
                'success-light': '#34d399',
                'success-dark': '#059669',
                danger: '#f43f5e',
                'danger-light': '#fb7185',
                'danger-dark': '#e11d48',
                warning: '#f59e0b',
                'warning-light': '#fbbf24',
                'warning-dark': '#d97706',
                // Text Hierarchy
                text: {
                    primary: '#f8fafc',
                    secondary: '#94a3b8',
                    tertiary: '#64748b',
                    muted: '#475569',
                },
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
            boxShadow: {
                'glow-success': '0 0 20px rgba(16, 185, 129, 0.15)',
                'glow-danger': '0 0 20px rgba(244, 63, 94, 0.15)',
                'card': '0 4px 6px -1px rgba(0, 0, 0, 0.3)',
                'card-hover': '0 20px 25px -5px rgba(0, 0, 0, 0.4)',
                'button': '0 4px 14px rgba(99, 102, 241, 0.25)',
                'button-hover': '0 6px 20px rgba(99, 102, 241, 0.4)',
            },
            animation: {
                'fade-in': 'fadeIn 0.3s ease-out',
                'slide-up': 'slideUp 0.4s ease-out',
                'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                pulseSoft: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.7' },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
