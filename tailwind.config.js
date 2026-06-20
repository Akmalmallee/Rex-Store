import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Instrument Sans', ...defaultTheme.fontFamily.sans],
                display: ['Instrument Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                gold: {
                    50: '#fdf8ed',
                    100: '#f9efd0',
                    200: '#f2dd9f',
                    300: '#e9c668',
                    400: '#e0b13d',
                    500: '#C8A951',
                    600: '#a88a35',
                    700: '#8c702b',
                    800: '#745d27',
                    900: '#634e23',
                },
                dark: {
                    50: '#1a1a1a',
                    100: '#111111',
                    200: '#0d0d0d',
                    300: '#0a0a0a',
                    400: '#080808',
                    500: '#050505',
                },
            },
            backdropBlur: {
                xs: '2px',
                glass: '24px',
            },
            animation: {
                'fade-in': 'fadeIn 0.6s ease-out forwards',
                'slide-up': 'slideUp 0.6s ease-out forwards',
                'slide-down': 'slideDown 0.4s ease-out forwards',
                'scale-in': 'scaleIn 0.4s ease-out forwards',
                shimmer: 'shimmer 2s infinite linear',
                'spin-slow': 'spin 3s linear infinite',
                'reveal-left': 'revealLeft 0.8s cubic-bezier(0.25, 0.1, 0.25, 1) forwards',
                'reveal-right': 'revealRight 0.8s cubic-bezier(0.25, 0.1, 0.25, 1) forwards',
                'reveal-scale': 'revealScale 0.8s cubic-bezier(0.25, 0.1, 0.25, 1) forwards',
                'parallax-up': 'parallaxUp 1.2s ease-out forwards',
                'glow-pulse': 'glowPulse 2s ease-in-out infinite',
                'border-glow': 'borderGlow 2s ease-in-out infinite',
                'curtain': 'curtain 0.8s cubic-bezier(0.25, 0.1, 0.25, 1) forwards',
                'ripple': 'ripple 0.6s ease-out',
                'page-enter': 'pageEnter 0.8s ease-out forwards',
                'loading-bar': 'loadingBar 2s ease-out forwards',
                'crossfade': 'crossfade 0.5s ease-out forwards',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideDown: {
                    '0%': { opacity: '0', transform: 'translateY(-10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                revealLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-40px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                revealRight: {
                    '0%': { opacity: '0', transform: 'translateX(40px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                revealScale: {
                    '0%': { opacity: '0', transform: 'scale(0.95)', filter: 'blur(4px)' },
                    '100%': { opacity: '1', transform: 'scale(1)', filter: 'blur(0)' },
                },
                parallaxUp: {
                    '0%': { transform: 'translateY(0)' },
                    '100%': { transform: 'translateY(-20px)' },
                },
                glowPulse: {
                    '0%, 100%': { boxShadow: '0 0 5px rgba(200,169,81,0.2)' },
                    '50%': { boxShadow: '0 0 20px rgba(200,169,81,0.4)' },
                },
                borderGlow: {
                    '0%, 100%': { borderColor: 'rgba(200,169,81,0.2)' },
                    '50%': { borderColor: 'rgba(200,169,81,0.6)' },
                },
                curtain: {
                    '0%': { clipPath: 'inset(0 100% 0 0)' },
                    '100%': { clipPath: 'inset(0 0 0 0)' },
                },
                ripple: {
                    '0%': { transform: 'scale(0)', opacity: '0.5' },
                    '100%': { transform: 'scale(4)', opacity: '0' },
                },
                pageEnter: {
                    '0%': { opacity: '0', transform: 'translateY(12px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                loadingBar: {
                    '0%': { width: '0%' },
                    '30%': { width: '30%' },
                    '60%': { width: '65%' },
                    '100%': { width: '100%' },
                },
                crossfade: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
            },
            backgroundImage: {
                'glass-gradient': 'linear-gradient(135deg, rgba(255,255,255,0.05), rgba(255,255,255,0.01))',
                'glass-gradient-strong': 'linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02))',
            },
        },
    },

    plugins: [forms],
};
